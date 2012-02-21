<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 *  Participation in a drink
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 *  @version 1.0 - 21 janv. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipation extends ModelInterface
{

    /**
     *  Drink of participation
     *
     *  @var    integer
     */
    protected $_drink_id    = null;

    /**
     *  Participating user
     *
     *  @var    integer
     */
    protected $_user_id     = null;

    /**
     *  Percentage chance for the user to be present to the drink
     *
     *  @var    integer
     */
    protected $_percentage  = null;

    /**
     *  Is it necessary to remind the user a drink?
     *
     *  @var    boolean
     */
    protected $_reminder    = null;

    /**
     *  Is the DrinkParticiaption new?
     *
     *  @var    boolean
     */
    protected $_is_new      = true;

    /**
     *  Find a DrinkParticipation
     *
     *  @param  Connection  $connection         Connection to the database
     *  @param  integer     $drink_id           The drink of the participation
     *  @param  integer     $user_id            The participant
     *  @return DrinkParticipation              The DrinkParticipation or null
     */
    public static function find(Connection $connection, $drink_id, $user_id)
    {
        if( null !== $this->_drink && null !== $this->_user )
        {
            $query  = 'SELECT drink_id, user_id, percentage, reminder '
                    . 'FROM Drink_Participation '
                    . 'WHERE drink_id = :drink_id AND user_id = :user_id;';
            $data   = $this->connection->fetchAssoc($query,
                                                    array(
                                                            ':drink_id' => $drink_id,
                                                            ':user_id'  => $user_id
                                                    ));
            if( !$data )
                return null;

            $n          = new self($connection);

            $n          ->setDrinkId((integer) $data['drink_id'])
                        ->setuserId((integer) $data['user_id'])
                        ->setPercentage((integer) $data['percentage'])
                        ->setReminder((boolean) $data['reminder']);

            $n->_is_new = false;

            return $n;
        }
    }

    /**
     *  Find all the DrinkParticipation of a Drink
     *
     *  @param  Connection  $connection         Connection to the database
     *  @param  integer     $drink              The drink of the participation
     */
    public static function findByDrinkId(Connection $connection, $drink_id)
    {
        $result = array();
        if( null !== $drink_id )
        {
            $query  = 'SELECT drink_id, user_id, percentage, reminder '
                    . 'FROM Drink_Participation '
                    . 'WHERE drink_id = :drink_id;';
            $data   = $connection->fetchAll($query,
                                            array(':drink_id' => $drink_id)
                                           );

            foreach( $data as $line )
            {
                $n          = new self($connection);

                $n          ->setDrinkId((integer) $line['drink_id']);
                $n          ->setuserId((integer) $line['user_id']);
                $n          ->setPercentage((integer) $line['percentage']);
                $n          ->setReminder((boolean) $line['reminder']);

                $result[]   = $n;
            }
        }

        return $result;
    }

    /**
     *  Find all the DrinkParticipation of an User
     *
     *  @param  Connection  $connection         Connection to the database
     *  @param  integer     $user               The user of the participation
     */
    public static function findByUserId(Connection $connection, $user_id)
    {
        $result = array();
        if( null !== $user_id )
        {
            $query  = 'SELECT drink_id, user_id, percentage, reminder '
                    . 'FROM User_Participation '
                    . 'WHERE user_id = :user_id;';
            $data   = $connection->fetchAll($query,
                                            array(':user_id' => $user_id)
                                           );

            foreach( $data as $line )
            {
                $n          = new self($connection);

                $n          ->setUserId((integer) $line['user_id']);
                $n          ->setuserId((integer) $line['user_id']);
                $n          ->setPercentage((integer) $line['percentage']);
                $n          ->setReminder((boolean) $line['reminder']);

                $result[]   = $n;
            }
        }

        return $result;
    }

    /**
     *  Save the DrinkParticipation.
     *
     *  @return boolean                         Is the save successful ?
     */
    public function save()
    {
        return $this->isNew() ? $this->insert() : $this->update();
    }

    /**
     *  Is the DrinkParticiaption new?
     *
     *  @return boolean                         Is the DrinkParticiaption new?
     */
    public function isNew()
    {
        return $this->_is_new;
    }

    /**
     *  Insert the DrinkParticipation.
     *
     *  @return boolean                         Is the insert successful ?
     */
    protected function insert()
    {
        return $this->connection->insert('User', array(
            'lastname' => $this->lastname,
            'firstname' => $this->firstname,
            'email' => $this->email,
            'token' => $this->token,
            'member_id' => $this->member_id,
        )) === 1;
    }

    /**
     *  Update the DrinkParticipation.
     *
     *  @return boolean                         Is the update successful ?
     */
    protected function update()
    {
        return $this->connection->update('DrinkParticipation', array(
            'percentage' => $this->_percentage,
            'reminder'   => $this->_reminder
        ), array(
            'drink_id'   => $this->_drink_id,
            'user_id'    => $this->_user_id
        )) === 1;
    }

    /**
     *  Delete the DrinkParticipation.
     *
     *  @return boolean                         Is the delete successful ?
     */
    protected function delete()
    {
        return $this->connection->delete('DrinkParticipation', array(
            'percentage' => $this->_percentage,
            'reminder'   => $this->_reminder
        ), array(
            'drink_id'   => $this->_drink_id,
            'user_id'    => $this->_user_id
        )) === 1;
    }

    /**
     *  Set the drink of participation
     *
     *  @param  integer             $drink_id   The drink of the participation
     *  @return DrinkParticipation              The participation
     */
    public function setDrinkId($drink_id)
    {
        $this->_drink_id = $drink_id;
        return $this;
    }

    /**
     *  Get the drink of participation
     *
     *  @return integer                         The drink of the participation
     */
    public function getDrinkId()
    {
        return $this->_drink_id;
    }

    /**
     *  Set the participating user
     *
     *  @param  integer             $user_id    The participating user
     *  @return DrinkParticipation              The participation
     */
    public function setUserId($user_id)
    {
        $this->_user_id = $user_id;
        return $this;
    }

    /**
     *  Get the participating user
     *
     *  @return integer                         The participating user
     */
    public function getUserId()
    {
        return $this->_user_id;
    }

    /**
     *  Set the percentage chance for the user to be present to the drink
     *
     *  @param  integer             $percentage The percentage chance for the user to be present to the drink
     *  @return DrinkParticipation              The participation
     */
    public function setPercentage($percentage)
    {
        $this->_percentage = $percentage;
        return $this;
    }

    /**
     *  Get the percentage chance for the user to be present to the drink
     *
     *  @return integer                     The percentage chance for the user to be present to the drink
     */
    public function getPercentage()
    {
        return $this->_percentage;
    }

    /**
     *  Set the is it necessary to remind the user a drink?
     *
     *  @param  integer             $reminder   The is it necessary to remind the user a drink?
     *  @return DrinkParticipation              The participation
     */
    public function setReminder($reminder)
    {
        $this->_reminder = $reminder;
        return $this;
    }

    /**
     *  Get the is it necessary to remind the user a drink?
     *
     *  @return integer                     The is it necessary to remind the user a drink?
     */
    public function getReminder()
    {
        return $this->_reminder;
    }
}
