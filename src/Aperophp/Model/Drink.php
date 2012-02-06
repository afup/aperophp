<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Drink model.
 *
 * @author Mikael Randy <mikael.randy@gmail.com>
 * @since 21 janv. 2012
 * @version 1.1 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
 */
class Drink extends ModelInterface
{
    const KIND_DRINK        = 'drink';
    const KIND_CONFERENCE   = 'conference';
    
    protected
        $id,
        $place,
        $day,
        $hour,
        $kind,
        $description,
        $map,
        $id_user,
        $id_city;

    static public function getKinds()
    {
        return array(
            self::KIND_DRINK        => 'Apéro',
            self::KIND_CONFERENCE   => 'Conférence',
        );
    }
    
    /**
     * Save.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 6 févr. 2012 
     * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }
    
    /**
     * Is new ?
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 6 févr. 2012 
     * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
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
     * @since 6 févr. 2012 
     * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function insert()
    {
        $stmt = $this->connection->insert('Drink', array(
            'place' => $this->place,
            'day' => $this->day,
            'hour' => $this->hour,
            'kind' => $this->kind,
            'description' => $this->description,
            'map' => $this->map,
            'id_user' => $this->id_user,
            'id_city' => $this->id_city,
        ));
    
        $this->id = $this->connection->lastInsertId();
    
        return $stmt;
    }
    
    /**
     * Update.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 6 févr. 2012 
     * @version 1.0 - 6 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function update()
    {
        return $this->connection->update('Drink', array(
            'place' => $this->place,
            'day' => $this->day,
            'hour' => $this->hour,
            'kind' => $this->kind,
            'description' => $this->description,
            'map' => $this->map,
            'id_user' => $this->id_user,
            'id_city' => $this->id_city,
        ), array('id' => $this->id));
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getPlace()
    {
        return $this->place;
    }
    
    public function getDay()
    {
        return $this->day;
    }
    
    public function getHour()
    {
        return $this->hour;
    }
    
    public function getKind()
    {
        return $this->kind;
    }
    
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getMap()
    {
        return $this->map;
    }
    
    public function getIdUser()
    {
        return $this->id_user;
    }
    
    public function getIdCity()
    {
        return $this->id_city;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    public function setPlace($place)
    {
        $this->place = $place;
        return $this;
    }
    
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }
    
    public function setHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }
    
    public function setKind($kind)
    {
        $this->kind = $kind;
        return $this;
    }
    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function setMap($map)
    {
        $this->map = $map;
        return $this;
    }
    
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }
    
    public function setIdCity($id_city)
    {
        $this->id_city = $id_city;
        return $this;
    }
}