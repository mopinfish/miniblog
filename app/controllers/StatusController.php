<?php

/**
 * StatusController
 */
class StatusController extends Controller
{
    /*
     * ログイン必須アクションを定義
     */
    protected $_authActions = array('index', 'post');

    /**
     * 投稿一覧アクション
     */
    public function indexAction()
    {
        $user = $this->_session->get('user');
        $statuses = $this->_dbManager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);
        return $this->pureRender(array(
            'errors' => array(),
            'statuses' => $statuses,
            'body' => '',
            '_token' => $this->generateCsrfToken('status/post')
        ));
    }

    /**
     * 投稿 登録処理アクション
     */
    public function postAction()
    {
        // POST通信でなかったら404ページへ
        if (!$this->_request->isPost()) {
            $this->forward404();
        }

        // CSRFトークンが正しくなかったら入力ページへリダイレクト
        $token = $this->_request->getPost('_token');
        if (!$this->checkCsrfToken('status/post', $token)) {
            return $this->redirect('/');
        }

        $body = $this->_request->getPost('body');

        $errors = array();
        // 投稿内容のバリデーション
        if (!strlen($body)) {
            $errors[] = 'ひとことを入力してください';
        } else if (mb_strlen($body) > 200) {
            $errors[] = 'ひとことは200文字以内で入力してください';
        }

        // エラーが一つもなければユーザーをDBに登録
        if (count($errors) === 0) {
            $user = $this->_session->get('user');
            $this->_dbManager->get('Status')->insert($user['id'], $body);

            $this->redirect('/');
        }

        $user = $this->_session->get('user');
        $statuses = $this->_dbManager->get('Status')->fetchAllPersonalArchivesByUserId($user['id']);

        // 投稿一覧の再表示
        return $this->pureRender(array(
            'errors' => $errors,
            'body' => $body,
            'statuses' => $statuses,
            '_token' => $this->generateCsrfToken('account/signup')
        ), 'index');
    }
    
    /**
     * ユーザー別投稿一覧
     */
    public function userAction($params)
    {
        $user = $this->_dbManager->get('User')->fetchByUserName($params['user_name']);
        if (!$user) {
            $this->forward404();
        }

        $statuses = $this->_dbManager->get('Status')->fetchAllByUserId($user['id']);

        return $this->pureRender(array(
            'user' => $user,
            'statuses' => $statuses
        ));
    }

    /**
     * 投稿詳細
     */
    public function showAction($params)
    {
        $status = $this->_dbManager->get('Status')->fetchByIdAndUserName($params['id'], $params['user_name']);

        if (!$status) {
            $this->forward404();
        }

        return $this->pureRender(array('status' => $status));
    }
}
