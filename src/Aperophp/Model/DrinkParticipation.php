<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 *  Participation in a drink
 *
 *  @author Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class DrinkParticipation extends ModelInterface
{

    /**
     *  Drink of participation
     *
     *  @var    Drink
     */
    protected $_drink = null;

    /**
     *  Participating member
     *
     *  @var    Member
     */
    protected $_member = null;

    /**
     *  Percentage chance to be present
     *
     *  @var    integer
     */
    protected $_percentage = null;

    /**
     *  Is it necessary to remind the member a drink?
     *
     *  @var    boolean
     */
    protected $_reminder = null;

    /**
     *  Constructor
     *  if the parameters are specified, attempts to retrieve from database
     *
     *  @param  Connection  $connection     Connection to the database
     *  @param  Drink       $drink          The drink of the participation
     *  @param  Member      $member         The participant
     */
    public function __construct(Connection $connection,
                                Drink $drink = null,
                                Member $member = null)
    {
        parent::__construct($connection);
        $this->_drink   = $drink;
        $this->_member  = $member;

        if( null !== $this->_drink && null !== $this->_member )
        {
            $query  = 'SELECT id_drink, id_member, percentage, reminder'
                    . 'FROM Drink_Participation'
                    . 'WHERE id_drink = :drink AND id_member = :member';
            $smt    = $this->connection->prepare($query);
            $smt    ->bindValue('drink',  $this->_drink->getId(),  'integer');
            $smt    ->bindValue('member', $this->_member->getId(), 'integer');

            if( $result = $smt->fetch() )
            {
                $this->_percentage  = (integer) $result['percentage'];
                $this->_reminder    = (boolean) $result['reminder'];
            }
        }
    }

    /**
     *  Set the drink of participation
     *
     *  @param  Drink               $drink  The drink of the participation
     *  @return DrinkParticipation          The participation
     */
    public function setDrink(Drink $drink)
    {
        $this->_drink = $drink;
        return $this;
    }

    /**
     *  Get the drink of participation
     *
     *  @return Drink                       The drink of the participation
     */
    public function getDrink()
    {
        return $this->_drink;
    }
}
