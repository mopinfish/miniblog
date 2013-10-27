<?php

/**
 * ミニブログサービス用アプリケーションクラス
 */
class MiniBlogApplication extends Application
{
    /**
     * ログインページ用コントローラ/アクション配列
     */
    protected $_loginAction = array('account', 'signin');

    /**
     * ルートディレクトリの首都rく
     */
    public function getRootDir()
    {
        return dirname(__FILE__);
    }

    /**
     * ルーティング定義の登録
     */
    public function registerRoutes()
    {
        return array(
            // StatusControllerの定義
            '/'
                => array('controller' => 'status', 'action' => 'index'),
            '/status/post'
                => array('controller' => 'status', 'action' => 'post'),
            '/user/:user_name'
                => array('controller' => 'status', 'action' => 'user'),
            '/user/:user_name/status/:id'
                => array('controller' => 'status', 'action' => 'show'),
            // AccountControllerの定義
            '/account'
                => array('controller' => 'account', 'action' => 'index'),
            '/follow'
                => array('controller' => 'account', 'action' => 'follow'),
            '/account/:action'
                => array('controller' => 'account')
        );
    }

    /**
     * アプリケーションごとの設定
     */
    public function configure()
    {
        $this->_dbManager->connect('master', array(
            'dsn'   => 'mysql:dbname=miniblog;host=localhost',
            'user'  => 'miniblog',
            'password'  => 'minimini'
        ));
    }
}
