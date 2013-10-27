<?php

/**
 * FOLLOWINGテーブル管理クラス
 */
class FollowingRepository extends DbRepository
{
    /**
     * フォロー対象ユーザの追加
     */
    public function insert($userId, $followingId)
    {
        $sql = "INSERT INTO following VALUES(:user_id, :following_id)";

        $stmt = $this->execute($sql, array(
            ':user_id' => $userId,
            ':following_id' => $followingId
        ));
    }

    /**
     * フォロー済みユーザの判定
     */
    public function isFollowing($userId, $followingId)
    {
        $ret = false;
        $sql = "SELECT count(user_id) as count
                FROM following
                WHERE user_id = :user_id AND
                following_id = :following_id
                ";
        $row = $this->fetch($sql, array(
            ':user_id' => $userId,
            ':following_id' => $followingId
        ));

        if ((int)$row['count'] === 1) {
            $ret = true;
        }
        return $ret;
    }
}
