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
        $id_city,
        $city,
        $user;

    static public function getKinds()
    {
        return array(
            self::KIND_DRINK        => 'Apéro',
            self::KIND_CONFERENCE   => 'Conférence',
        );
    }
    
    /**
     * Find all order by day.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012 
     * @version 1.1 - 11 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     */
    static public function findAll(Connection $connection, $limit = null)
    {
        $sql = "SELECT * FROM Drink ORDER BY day DESC";
        $sql .= $limit ? " LIMIT " . $limit : "";
        
        $aData = $connection->fetchAll($sql);
        
        $aDrink = array();
        foreach ($aData as $data)
        {
            $oDrink = new self($connection);
            $oDrink
                ->setId($data['id'])
                ->setPlace($data['place'])
                ->setDay($data['day'])
                ->setHour($data['hour'])
                ->setKind($data['kind'])
                ->setDescription($data['description'])
                ->setMap($data['map'])
                ->setIdUser($data['id_user'])
                ->setIdCity($data['id_city']);
            
            $aDrink[$data['id']] = $oDrink;
        }
        
        return $aDrink;
    }
    
    /**
     * Find one by id.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012 
     * @version 1.0 - 7 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param integer $id
     */
    static public function findOneById(Connection $connection, $id)
    {
        $data = $connection->fetchAssoc('SELECT * FROM Drink WHERE id = ?', array($id));
    
        if (!$data)
        {
            return false;
        }
    
        $oDrink = new self($connection);
        
        $oDrink
            ->setId($data['id'])
            ->setPlace($data['place'])
            ->setDay($data['day'])
            ->setHour($data['hour'])
            ->setKind($data['kind'])
            ->setDescription($data['description'])
            ->setMap($data['map'])
            ->setIdCity($data['id_city'])
            ->setIdUser($data['id_user']);
    
        return $oDrink;
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
    
    /**
     * Get kind translated.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012 
     * @version 1.0 - 7 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return string
     */
    public function getKindTranslated()
    {
        $kinds = self::getKinds();
        return array_key_exists($this->kind, $kinds) ? $kinds[$this->kind] : '';
    }
    
    /**
     * Get city associated.
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012 
     * @version 1.0 - 7 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return City
     */
    public function getCity()
    {
        if (!$this->city)
        {
            $this->city = City::findOneById($this->connection, $this->id_city);
        }
        
        return $this->city;
    }
    
    /**
     * Get user associated
     * 
     * @author Koin <pkoin.koin@gmail.com>
     * @since 8 févr. 2012 
     * @version 1.0 - 8 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return Ambigous <boolean, \Aperophp\Model\City>
     */
    public function getUser()
    {
        if (!$this->user)
        {
            $this->user = User::findOneById($this->connection, $this->id_user);
        }
    
        return $this->user;
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