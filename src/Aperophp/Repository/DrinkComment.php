<?php

namespace Aperophp\Repository;

class DrinkComment extends Repository
{
    public function getTableName()
    {
        return 'Drink_Comment';
    }

    public function findByDrinkId($drinkId, $hideSpam = false)
    {
        $sql = 'SELECT c.*, u.email as user_email, u.firstname,
                (SELECT username FROM Member m WHERE m.id = u.member_id) as username
                FROM Drink_Comment c, User u
                WHERE c.user_id = u.id AND drink_id = ?
            ';

        if ($hideSpam) {
            $sql .= ' AND c.is_spam = 0 ';
        }

        $sql .= ' ORDER BY created_at';

        $params = array((int) $drinkId);

        return $this->db->fetchAll($sql, $params);
    }

    public function countByUserId($userId, $hideSpam=false)
    {
        $sql = '
            SELECT
                COUNT(1) AS count
            FROM
                Drink_Comment AS c
            WHERE
                c.user_id = ?
        ';

        if ($hideSpam) {
            $sql .= 'AND c.is_spam = 0';
        }

        return $this->db->fetchColumn($sql, [(int) $userId]);
    }

    public function findOne($drinkId, $userId)
    {
        $sql = 'SELECT * FROM Drink_Comment WHERE drink_id = ? AND user_id = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array((int) $drinkId, (int) $userId));
    }

    public function groupByEmail($email, $userId)
    {
        $sql = 'UPDATE Drink_Comment SET user_id = ? WHERE user_id IN (SELECT id FROM User WHERE email = ?)';

        $this->db->prepare($sql)->execute(array((int) $userId, $email));
    }

    /**
     * Remove comments flaged as spam
     *
     * @return int
     */
    public function removeSpam()
    {
        $sql = 'DELETE FROM '.$this->getTableName().' WHERE is_spam = 1';
        return $this->db->prepare($sql)->execute();
    }

    /**
     * Get all comments who are not spam
     *
     * @return array
     */
    public function getToCheckSpam()
    {
        $sql = '
            SELECT
                Drink_Comment.id AS comment_id,
                Drink_Comment.content,
                User.id AS user_id,
                User.lastname,
                User.firstname,
                User.email,
                Member.id AS member_id,
                Member.username
            FROM
                Drink_Comment
                JOIN User ON Drink_Comment.user_id = User.id
                LEFT JOIN Member ON User.member_id = Member.id
            WHERE
                is_spam = 0
            ORDER BY Drink_Comment.id DESC
        ';
        return $this->db->fetchAll($sql);
    }

    public function markAsSpam($id)
    {
        $sql = 'UPDATE Drink_Comment SET is_spam = 1 WHERE id = ?';
        $this->db->prepare($sql)->execute([(int) $id]);
    }
}
