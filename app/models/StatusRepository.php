<?php

/**
 * STATUSテーブル管理クラス
 */
class StatusRepository extends DbRepository
{
    /**
     * 記事投稿
     */
    public function insert($userId, $body)
    {
        $now = new DateTime();
        $sql = "
            INSERT INTO `status` (user_id, body, created_at)
            VALUES (:user_id, :body, :created_at)
            ";
        $stmt = $this->execute($sql, array(
            ':user_id' => $userId,
            ':body' => $body,
            ':created_at' => $now->format('Y-m-d H:i:s')
        ));
    }

    /**
     * ユーザーにひもづく全ての投稿を取得
     */
    public function fetchAllPersonalArchivesByUserId($userId)
    {
        $sql = "
            SELECT a.*, u.user_name
            FROM `status` a LEFT JOIN `user` u ON a.user_id = u.id
            WHERE u.id = :user_id
            ORDER BY a.created_at DESC
            ";
        return $this->fetchAll($sql, array(':user_id' => $userId));
    }

    /**
     * ユーザの投稿一覧を取得
     */
    public function fetchAllByUserId($userId)
    {
        $sql = "
            SELECT a.*, u.user_name
            FROM status a LEFT JOIN user u ON a.user_id = u.id
            WHERE u.id = :user_id
            ORDER BY a.created_at DESC
            ";
        return $this->fetchAll($sql, array(':user_id' => $userId));
    }

    /**
     * 投稿IDとユーザー名にひもづく投稿を1件取得
     */
    public function fetchByIdAndUserName($id, $userName)
    {
        $sql = "
            SELECT a.*, u.user_name
            FROM status a LEFT JOIN user u ON a.user_id = u.id
            WHERE a.id = :id
                AND u.user_name = :user_name
            ";
        return $this->fetch($sql, array(
            ':id' => $id,
            ':user_name' => $userName
        ));
    }
}
