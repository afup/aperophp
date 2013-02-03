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
     * checkUserPassword
     *
     * @param string $username
     * @param string $password
     *
     * @return int
     */
    public function checkUserPassword($username, $password)
    {

        $sql = 'SELECT COUNT(*) FROM ' . $this->getTableName() . ' WHERE username = ? AND password = ? LIMIT 1';

        return $this->db->fetchColumn($sql, array($username, $password));
    }
}
