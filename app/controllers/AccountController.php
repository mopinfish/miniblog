<?php

/**
 * AccountController
 */
class AccountController extends Controller
{
    /*
     * ログイン必須アクションを定義
     */
    protected $_authActions = array('index', 'singout', 'follow');

    /**
     * アカウント トップページアクション
     */
    public function indexAction() {
        $user = $this->_session->get('user');
        $followings = $this->_dbManager->get('User')->fetchAllFollowingsByUserId($user['id']);
        return $this->render(array(
            'user' => $user,
            'followings' => $followings
        ));
    }

    /**
     * アカウント ログインアクション
     */
    public function signinAction()
    {
        // 既にログイン済みならアカウントトップページへリダイレクト
        if ($this->_session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render(array(
            'userName' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signin')
        ));
    }

    /**
     * アカウント 認証アクション
     */
    public function authenticateAction()
    {
        // 既にログイン済みならアカウントトップページへリダイレクト
        if ($this->_session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        // POST通信でなかったら404ページへ
        if (!$this->_request->isPost()) {
            $this->forward404();
        }

        // CSRFトークンが正しくなかったらログインページへリダイレクト
        $token = $this->_request->getPost('_token');
        if (!$this->checkCsrfToken('account/signin', $token)) {
            return $this->redirect('/account/signin');
        }

        $userName = $this->_request->getPost('user_name');
        $password = $this->_request->getPost('password');

        $errors = array();
        // ユーザーIDのバリデーション
        if (!strlen($userName)) {
            $errors[] = 'ユーザーIDを入力してください';
        }
        // パスワードのバリデーション
        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        }

        if (count($errors) === 0) {
            $userRepository = $this->_dbManager->get('User');
            $user = $userRepository->fetchByUserName($userName);

            if (!$user || $user['password'] !== $userRepository->hashPassword($password)) {
                $errors[] = 'ユーザーIDかパスワードが不正です';
            } else {
                $this->_session->setAuthenticated(true);
                $this->_session->set('user', $user);

                return $this->redirect('/');
            }
        }

        return $this->render(array(
            'userName' => $userName,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signin')
        ), 'signin');
    }

    /**
     * アカウント ログアウトアクション
     */
    public function signoutAction()
    {
        $this->_session->clear();
        $this->_session->setAuthenticated(false);

        return $this->redirect('/account/signin');
    }

    /**
     * アカウント 登録フォームアクション
     */
    public function signupAction()
    {
        // 既にログイン済みならアカウントトップページへリダイレクト
        if ($this->_session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        return $this->render(array(
            'userName' => '',
            'password' => '',
            '_token' => $this->generateCsrfToken('account/signup')
        ));
    }

    /**
     * ユーザ登録 登録処理アクション
     */
    public function registerAction()
    {
        // 既にログイン済みならアカウントトップページへリダイレクト
        if ($this->_session->isAuthenticated()) {
            return $this->redirect('/account');
        }

        // POST通信でなかったら404ページへ
        if (!$this->_request->isPost()) {
            $this->forward404();
        }

        // CSRFトークンが正しくなかったら入力ページへリダイレクト
        $token = $this->_request->getPost('_token');
        if (!$this->checkCsrfToken('account/signup', $token)) {
            return $this->redirect('/account/signup');
        }

        $userName = $this->_request->getPost('user_name');
        $password = $this->_request->getPost('password');

        $errors = array();
        // ユーザーIDのバリデーション
        if (!strlen($userName)) {
            $errors[] = 'ユーザーIDを入力してください';
        } else if (!preg_match('/^\w{3,20}$/', $userName)) {
            $errors[] = 'ユーザーIDは半角英数字およびアンダースコアを3～20文字以内で入力してください';
        } else if (!$this->_dbManager->get('User')->isUniqueUserName($userName)) {
            $errors[] = 'ユーザーIDは既に使用されています';
        }
        // パスワードのバリデーション
        if (!strlen($password)) {
            $errors[] = 'パスワードを入力してください';
        } else if (4 > strlen($password) || strlen($password) > 30) {
            $errors[] = 'パスワードは4～30文字以内で入力してください';
        }

        // エラーが一つもなければユーザーをDBに登録
        if (count($errors) === 0) {
            $this->_dbManager->get('User')->insert($userName, $password);
            $this->_session->setAuthenticated(true);
            $user = $this->_dbManager->get('User')->fetchByUserName($userName);
            $this->_session->set('user', $user);

            $this->redirect('/');
        }

        // 入力ページの再表示
        return $this->render(array(
            'userName' => $userName,
            'password' => $password,
            'errors' => $errors,
            '_token' => $this->generateCsrfToken('account/signup')
        ), 'signup');
    }

    /**
     * フォローアクション
     */
    public function followAction()
    {
        // POST通信でなかったら404ページへ
        if (!$this->_request->isPost()) {
            $this->forward404();
        }

        // フォロー対象ユーザ名の存在チェック
        $followingName = $this->_request->getPost('following_name');
        if (!$followingName) {
            $this->forward404();
        }

        // CSRFトークンが正しくなかったら入力ページへリダイレクト
        $token = $this->_request->getPost('_token');
        if (!$this->checkCsrfToken('account/follow', $token)) {
            return $this->redirect('/user/' . $followingName);
        }

        $followUser = $this->_dbManager->get('User')->fetchByUserName($followingName);
        if (!$followUser) {
            $this->forward404();
        }

        $user = $this->_session->get('user');
        $followingRepository = $this->_dbManager->get('Following');
        // フォロー対象ユーザが自分以外、かつフォロー済みでない場合のみ、レコードに追加
        if ($user['id'] !== $followUser['id'] && !$followingRepository->isFollowing($user['id'], $followUser['id'])) {
            $followingRepository->insert($user['id'], $followUser['id']);
        }

        return $this->redirect('/account');
    }
}
