<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Drink comment model.
 *
 * @author Koin <pkoin.koin@gmail.com>
 * @since 18 févr. 2012 
 * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class DrinkComment extends ModelInterface
{
    protected 
        $id,
        $created_at,
        $content,
        $id_drink,
        $id_user,
        $user;
    
    /**
     * Save.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012 
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }
    
    /**
     * Is new ?
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012 
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
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
     * @since 18 févr. 2012 
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function insert()
    {
        $stmt = $this->connection->insert('Drink_Comment', array(
            'created_at' => $this->created_at,
            'content' => $this->content,
            'id_drink' => $this->id_drink,
            'id_user' => $this->id_user,
        ));
        
        $this->id = $this->connection->lastInsertId();
        
        return $stmt;
    }
    
    /**
     * Update.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012 
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function update()
    {
        return $this->connection->update('Drink_Comment', array(
            'created_at' => $this->created_at,
            'content' => $this->content,
            'id_drink' => $this->id_drink,
            'id_user' => $this->id_user,
        ), array('id' => $this->id));
    }
    
    /**
     * Return user associated.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 18 févr. 2012 
     * @version 1.0 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return \Aperophp\Model\User
     */
    public function getUser()
    {
        if (!$this->user)
            $this->user = User::findOneByMemberId($this->connection, $this->id_user);
        
        return $this->user;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function getIdDrink()
    {
        return $this->id_drink;
    }
    
    public function getIdUser()
    {
        return $this->id_user;
    }
    
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
        return $this;
    }
    
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }
    
    public function setIdDrink($id_drink)
    {
        $this->id_drink = $id_drink;
        return $this;
    }
    
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }
}