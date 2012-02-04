<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Member model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 22 janv. 2012 
 * @version 1.0 - 22 janv. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Member extends ModelInterface
{
    protected 
        $id,
        $username,
        $password,
        $active;
    
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
        return $this->connection->update('User', array(
            'username' => $this->username,
            'password' => $this->password,
            'active' => $this->active,
        ), array('id' => $this->id));
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