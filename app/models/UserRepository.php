<?php

/**
 * USERテーブル管理クラス
 */
class UserRepository extends DbRepository
{
    /**
     * 新規ユーザー登録
     */
    public function insert($name, $password)
    {
        $password = $this->hashPassword($password);
        $now = new DateTime();

        $sql = "
            INSERT INTO user(user_name, password, created_at)
            VALUES(:user_name, :password, :created_at)
            ";
        $stmt = $this->execute($sql, array(
            ':user_name' => $name,
            ':password' => $password,
            ':created_at' => $now->format('Y-m-d H:i:s')
        ));
    }

    /**
     * パスワードを暗号化
     */
    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    /**
     * ユーザー名からユーザーレコードを取得
     */
    public function fetchByUserName($name)
    {
        $sql = "SELECT * FROM user WHER user_name = :user_name";
        return $this->fetch($sql, array(':user_name' => $name);
    }

    /**
     * ユーザ名の重複チェック
     */
    isUniqueUserName($name)
    {
        $sql = "SELECT COUNT(id) as count FROM user WHERE user_name = :user_name";
        $row = $this->fetch($sql, array(':user_name' => $name));
        if ((int)$row['count'] === 0) {
            return true;
        }
        return false;
    }
}

