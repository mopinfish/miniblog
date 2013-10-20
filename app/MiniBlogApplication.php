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
            '/account'
                => array('controller' => 'account', 'action' => 'index'),
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
