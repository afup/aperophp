<?php

namespace Aperophp\Repository;

class User extends Repository
{
    public function getTableName()
    {
        return 'User';
    }

    /**
     * findOneByMemberId
     *
     * @param mixed $memberId
     *
     * @return array
     */
    public function findOneByMemberId($memberId)
    {
        $sql = 'SELECT * FROM User WHERE member_id = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array((int) $memberId));
    }

    /**
     * findOneByEmailToken
     *
     * @param mixed $email
     * @param mixed $token
     *
     * @return array
     */
    public function findOneByEmailToken($email, $token)
    {
        $sql = 'SELECT * FROM User WHERE email = ? AND token = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array($email, $token));
    }

    /**
     * findOneByEmail
     *
     * @param mixed $email
     *
     * @return array
     */
    public function findOneByEmail($email)
    {
        $sql = 'SELECT * FROM User WHERE email = ? LIMIT 1';

        return $this->db->fetchAssoc($sql, array($email));
    }

    /**
     * removeUsers
     *
     * @param mixed $email
     * @param integer $userId
     */
    public function removeUsers($email, $userId)
    {
        $sql = 'DELETE FROM User WHERE id <> ? AND email = ?';

        $this->db->prepare($sql)->execute(array((int) $userId, $email));
    }

}
