<?php

namespace Aperophp\Repository;

class Member extends Repository
{
    public function getTableName()
    {
        return 'Member';
    }

    /**
     * findOneByUsernameAndPassword
     *
     * @param string $username
     * @param string $password
     *
     * @return array
     */
    public function findOneByUsernameAndPassword($username, $password)
    {
        $sql = 'SELECT * FROM Member WHERE username = ? AND password = ? AND active = true LIMIT 1';

        return $this->db->fetchAssoc($sql, array($username, $password));
    }

    /**
     * findOneByUsername
     *
     * @param string $username
     *
     * @return array
     */
    public function findOneByUsername($username)
    {
        $sql = 'SELECT * FROM Member WHERE username = ? AND active = true LIMIT 1';

        return $this->db->fetchAssoc($sql, array($username));
    }

}
