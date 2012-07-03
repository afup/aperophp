<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * User model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012
 * @version 1.4 - 21 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class User extends ModelInterface
{
    protected $id;
    protected $lastname;
    protected $firstname;
    protected $email;
    protected $token;
    protected $memberId;
    protected $member;

    /**
     * Find one user by member id.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 4 févr. 2012
     * @version 1.0 - 4 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param integer $member_id
     * @return User
     */
    static public function findOneByMemberId($connection, $memberId)
    {
        $data = $connection->fetchAssoc('SELECT * FROM User WHERE member_id = ?', array($memberId));

        if (!$data) {
            return null;
        }

        $oUser = new User($connection);

        $oUser
            ->setId($data['id'])
            ->setLastname($data['lastname'])
            ->setFirstname($data['firstname'])
            ->setEmail($data['email'])
            ->setToken($data['token'])
            ->setMemberId($data['member_id']);

        return $oUser;
    }

    /**
     * Find one user by id.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 8 févr. 2012
     * @version 1.0 - 8 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param integer $id
     * @return User
     */
    static public function findOneById(Connection $connection, $id)
    {
        $data = $connection->fetchAssoc('SELECT * FROM User WHERE id = ?', array($id));

        if (!$data) {
            return null;
        }

        $oUser = new User($connection);

        $oUser
            ->setId($data['id'])
            ->setLastname($data['lastname'])
            ->setFirstname($data['firstname'])
            ->setEmail($data['email'])
            ->setToken($data['token'])
            ->setMemberId($data['member_id']);

        return $oUser;
    }

    /**
     * Find one user by email/token.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 21 mars 2012
     * @version 1.0 - 21 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @param Connection $connection
     * @param string $email
     * @param string $token
     * @return User
     */
    static public function findOneByEmailToken(Connection $connection, $email, $token)
    {
        $data = $connection->fetchAssoc('SELECT * FROM User WHERE email = ? AND token = ?', array($email, $token));

        if (!$data) {
            return null;
        }

        $oUser = new User($connection);

        $oUser
            ->setId($data['id'])
            ->setLastname($data['lastname'])
            ->setFirstname($data['firstname'])
            ->setEmail($data['email'])
            ->setToken($data['token'])
            ->setMemberId($data['member_id']);

        return $oUser;
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
        $stmt = $this->connection->insert('User', array(
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'email' => $this->email,
            'token' => $this->token,
            'member_id' => $this->memberId,
        ));

        $this->id = $this->connection->lastInsertId();

        return 1 === $stmt;
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
        return 1 === $this->connection->update('User', array(
            'lastname'  => $this->lastname,
            'firstname' => $this->firstname,
            'email'     => $this->email,
            'token'     => $this->token,
            'member_id' => $this->memberId,
        ), array(
            'id' => $this->id
        ));
    }

    /**
     * Return member associated.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 20 mars 2012
     * @version 1.0 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     */
    public function getMember()
    {
        if (!$this->member) {
            $this->member = Member::findOneById($this->connection, $this->memberId);
        }

        return $this->member;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function getFirstname()
    {
        return $this->firstname;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getMemberId()
    {
        return $this->member_id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function setMemberId($memberId)
    {
        $this->memberId = $memberId;

        return $this;
    }
}
