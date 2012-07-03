<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 *  Participation in a drink
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.1 - 23 janv. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipation extends ModelInterface
{

    /**
     *  Drink of participation
     *
     *  @var integer
     */
    protected $drinkId = null;

    /**
     *  Participating user
     *
     *  @var integer
     */
    protected $userId = null;

    /**
     *  Percentage chance for the user to be present to the drink
     *
     *  @var integer
     */
    protected $percentage = null;

    /**
     *  Is it necessary to remind the user a drink?
     *
     *  @var boolean
     */
    protected $reminder = true;

    /**
     *  Is the DrinkParticiaption new?
     *
     *  @var boolean
     */
    protected $isNew = true;

    /**
     *  Drink of participation
     *
     *  @var \Aperophp\Model\Drink
     */
    protected $drink = null;

    /**
     *  Participating user
     *
     *  @var \Aperophp\Model\User
     */
    protected $user = null;

    /**
     *  Find a DrinkParticipation
     *
     *  @param Connection $connection Connection to the database
     *  @param integer    $drinkId    The drink of the participation
     *  @param integer    $userId     The participant
     *
     *  @return DrinkParticipation The DrinkParticipation or null
     */
    public static function find(Connection $connection, $drinkId, $userId)
    {
        $query  = 'SELECT drink_id, user_id, percentage, reminder '
            . 'FROM Drink_Participation '
            . 'WHERE drink_id = :drink_id AND user_id = :user_id;';

        $data = $connection->fetchAssoc($query, array(
            ':drink_id' => $drinkId,
            ':user_id'  => $userId
        ));

        if (!$data) {
            return null;
        }

        $n = new self($connection);

        $n->setDrinkId((integer) $data['drink_id'])
            ->setUserId((integer) $data['user_id'])
            ->setPercentage((integer) $data['percentage'])
            ->setReminder((boolean) $data['reminder']);

        $n->isNew = false;

        return $n;
    }

    /**
     *  Find all the DrinkParticipation of a Drink
     *
     *  @param Connection $connection Connection to the database
     *  @param integer    $drinkId    The drink of the participation
     *
     *  @return array
     */
    public static function findByDrinkId(Connection $connection, $drinkId)
    {
        $query  = 'SELECT drink_id, user_id, percentage, reminder FROM Drink_Participation WHERE drink_id = :drink_id;';
        $data   = $connection->fetchAll($query,
            array(':drink_id' => $drinkId)
        );

        $result = array();
        foreach ($data as $line) {
            $n = new self($connection);
            $n->setDrinkId((integer) $line['drink_id']);
            $n->setuserId((integer) $line['user_id']);
            $n->setPercentage((integer) $line['percentage']);
            $n->setReminder((boolean) $line['reminder']);

            $result[] = $n;
        }

        return $result;
    }

    /**
     *  Find all the DrinkParticipation of an User
     *
     *  @param Connection $connection Connection to the database
     *  @param integer    $userId     The user of the participation
     *
     *  @return array
     */
    public static function findByUserId(Connection $connection, $userId)
    {
        $query  = 'SELECT drink_id, user_id, percentage, reminder FROM Drink_Participation WHERE user_id = :user_id;';
        $data   = $connection->fetchAll($query,
            array(':user_id' => $userId)
        );

        $result = array();
        foreach ($data as $line) {
            $n = new self($connection);

            $n->setUserId((integer) $line['user_id']);
            $n->setuserId((integer) $line['user_id']);
            $n->setPercentage((integer) $line['percentage']);
            $n->setReminder((boolean) $line['reminder']);

            $result[] = $n;
        }

        return $result;
    }

    /**
     *  Save the DrinkParticipation.
     *
     *  @return boolean Is the save successful ?
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     *  Is the DrinkParticiaption new?
     *
     *  @return boolean Is the DrinkParticiaption new?
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     *  Insert the DrinkParticipation.
     *
     *  @return boolean Is the insert successful ?
     */
    protected function insert()
    {
        return $this->connection->insert('Drink_Participation', array(
            'drink_id'   => $this->drinkId,
            'user_id'    => $this->userId,
            'percentage' => $this->percentage,
            'reminder'   => $this->reminder
        )) === 1;
    }

    /**
     *  Update the DrinkParticipation.
     *
     *  @return boolean Is the update successful ?
     */
    protected function update()
    {
        return $this->connection->update('Drink_Participation', array(
            'percentage' => $this->percentage,
            'reminder'   => $this->reminder
        ), array(
            'drink_id'   => $this->drinkId,
            'user_id'    => $this->userId
        )) === 1;
    }

    /**
     *  Delete the DrinkParticipation.
     *
     *  @return boolean Is the delete successful ?
     */
    public function delete()
    {
        return $this->connection->delete('Drink_Participation', array(
            'percentage' => $this->percentage,
            'reminder'   => $this->reminder,
            'drink_id'   => $this->drinkId,
            'user_id'    => $this->userId
        )) === 1;
    }

    /**
     * Return user associated.
     *
     *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     *  @version 1.1 - 23 janv. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     *  @return \Aperophp\Model\User
     */
    public function getUser()
    {
        if (!$this->user) {
            $this->user = User::findOneById($this->connection, $this->userId);
        }

        return $this->user;
    }

    /**
     * Return drink associated.
     *
     *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     *  @version 1.1 - 23 janv. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     *  @return \Aperophp\Model\Drink
     */
    public function getDrink()
    {
        if (!$this->drink) {
            $this->drink = Drink::findOneById($this->connection, $this->drinkId);
        }

        return $this->drink;
    }

    /**
     *  Set the drink of participation
     *
     *  @param integer $drinkId The drink of the participation
     *
     *  @return DrinkParticipation The participation
     */
    public function setDrinkId($drinkId)
    {
        $this->drinkId = $drinkId;

        return $this;
    }

    /**
     *  Get the drink of participation
     *
     *  @return integer The drink of the participation
     */
    public function getDrinkId()
    {
        return $this->drinkId;
    }

    /**
     *  Set the participating user
     *
     *  @param integer $userId The participating user
     *
     *  @return DrinkParticipation The participation
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     *  Get the participating user
     *
     *  @return integer The participating user
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     *  Set the percentage chance for the user to be present to the drink
     *
     *  @param integer $percentage The percentage chance for the user to be present to the drink
     *
     *  @return DrinkParticipation The participation
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;

        return $this;
    }

    /**
     *  Get the percentage chance for the user to be present to the drink
     *
     *  @return integer The percentage chance for the user to be present to the drink
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     *  Set the is it necessary to remind the user a drink?
     *
     *  @param integer $reminder The is it necessary to remind the user a drink?
     *
     *  @return DrinkParticipation The participation
     */
    public function setReminder($reminder)
    {
        $this->reminder = $reminder;

        return $this;
    }

    /**
     *  Get the is it necessary to remind the user a drink?
     *
     *  @return integer The is it necessary to remind the user a drink?
     */
    public function getReminder()
    {
        return $this->reminder;
    }
}
