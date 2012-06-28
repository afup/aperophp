<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Member model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012
 * @version 1.2 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Member extends ModelInterface
{
    protected
        $id,
        $username,
        $password,
        $active,
        $user;

    /**
     * Find one member by username.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param string $username
     * @return Member
     */
    static public function findOneByUsername($connection, $username)
    {
        $data = $connection->fetchAssoc('SELECT * FROM Member WHERE username = ?', array($username));

        if (!$data)
        {
            return false;
        }

        $oMember = new Member($connection);

        $oMember
            ->setId($data['id'])
            ->setUsername($data['username'])
            ->setPassword($data['password'])
            ->setActive($data['active']);

        return $oMember;
    }

    /**
     * Find one member by id.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 20 mars. 2012
     * @version 1.0 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     *
     * @param   Connection  $connection
     * @param   integer     $id
     * @return  Member
     */
    static public function findById($connection, $id)
    {
        $data = $connection->fetchAssoc('SELECT * FROM Member WHERE id = ?', array((integer)$id));

        if (!$data)
            return null;

        $oMember = new Member($connection);

        $oMember
            ->setId($data['id'])
            ->setUsername($data['username'])
            ->setPassword($data['password'])
            ->setActive($data['active']);

        return $oMember;
    }

    /**
     * Save.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     * Is new ?
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return boolean
     */
    public function isNew()
    {
        return $this->id ? false : true;
    }

    /**
     * Insert.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function insert()
    {
        $stmt = $this->connection->insert('Member', array(
            'username' => $this->username,
            'password' => $this->password,
            'active' => $this->active,
        ));

        $this->id = $this->connection->lastInsertId();

        return $stmt;
    }

    /**
     * Update.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function update()
    {
        return $this->connection->update('Member', array(
            'username' => $this->username,
            'password' => $this->password,
            'active' => $this->active,
        ), array('id' => $this->id));
    }

    /**
     * Return user associated.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function getUser()
    {
        if (!$this->user)
            $this->user = User::findOneByMemberId($this->connection, $this->id);

        return $this->user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }
}
