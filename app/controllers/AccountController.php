<?php

/**
 * AccountController
 */
class AccountController extends Controller
{
    /**
     * ユーザー登録 入力アクション
     */
    public function signupAction()
    {
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
        } else if (!preg_match('/^\w(3, 20)$/', $userName)) {
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
}
