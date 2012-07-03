<?php

namespace Aperophp\Model;

use Doctrine\DBAL\Connection;

/**
 * Drink model.
 *
 * @author Mikael Randy <mikael.randy@gmail.com>
 * @since 21 janv. 2012
 * @version 1.4 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
 */
class Drink extends ModelInterface
{
    const KIND_DRINK        = 'drink';
    const KIND_CONFERENCE   = 'conference';

    protected $id;
    protected $place;
    protected $address;
    protected $day;
    protected $hour;
    protected $kind;
    protected $description;
    protected $latitude;
    protected $longitude;
    protected $userId;
    protected $cityId;
    protected $city;
    protected $member;
    protected $comments;
    protected $participations;

    static public function getKinds()
    {
        return array(
            self::KIND_DRINK      => 'Apéro',
            self::KIND_CONFERENCE => 'Conférence',
        );
    }

    /**
     * Find all order by day.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012
     * @version 1.2 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     */
    static public function findAll(Connection $connection, $limit = null)
    {
        $sql = 'SELECT * FROM Drink ORDER BY day DESC';
        $sql .= $limit ? ' LIMIT ' . $limit : '';

        $aData = $connection->fetchAll($sql);

        $aDrink = array();
        foreach ($aData as $data) {
            $oDrink = new self($connection);
            $oDrink
                ->setId($data['id'])
                ->setPlace($data['place'])
                ->setAddress($data['address'])
                ->setDay($data['day'])
                ->setHour($data['hour'])
                ->setKind($data['kind'])
                ->setDescription($data['description'])
                ->setLatitude($data['latitude'])
                ->setLongitude($data['longitude'])
                ->setUserId($data['user_id'])
                ->setCityId($data['city_id']);

            $aDrink[$data['id']] = $oDrink;
        }

        return $aDrink;
    }

    /**
     * Find all order by day and join the DrinkParticipations.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 20 mars 2012
     * @version 1.1 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @param Connection $connection
     */
    static public function findAllJoinParticipants(Connection $connection, $limit = null)
    {
        $sql  = 'SELECT *, D.user_id as c_id, P.user_id AS p_id FROM Drink D ';
        $sql .= 'LEFT OUTER JOIN Drink_Participation P ON D.id = P.drink_id ';
        $sql .= $limit ? 'JOIN (SELECT id FROM Drink ORDER BY day DESC LIMIT ' . $limit . ') t ON t.id = D.id ' : '';
        $sql .= 'ORDER BY day DESC';

        $aData = $connection->fetchAll($sql);

        $aDrink = array();
        foreach ($aData as $data) {
            if (!array_key_exists($data['id'], $aDrink)) {
                $oDrink = new self($connection);
                $oDrink
                    ->setId($data['id'])
                    ->setPlace($data['place'])
                    ->setAddress($data['address'])
                    ->setDay($data['day'])
                    ->setHour($data['hour'])
                    ->setKind($data['kind'])
                    ->setDescription($data['description'])
                    ->setLatitude($data['latitude'])
                    ->setLongitude($data['longitude'])
                    ->setUserId($data['c_id'])
                    ->setCityId($data['city_id']);
                $oDrink
                    ->participations = array();

                $aDrink[$data['id']] = $oDrink;
            }

            if (!empty($data['drink_id'])) {
                $oDrinkParticipation = new DrinkParticipation($connection);
                $oDrinkParticipation
                    ->setDrinkId((integer) $data['drink_id'])
                    ->setUserId((integer) $data['p_id'])
                    ->setPercentage((integer) $data['percentage'])
                    ->setReminder((boolean) $data['reminder']);
                $aDrink[$data['id']]->participations[] = $oDrinkParticipation;
            }
        }

        return $aDrink;
    }

    /**
     * Find one by id.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 7 févr. 2012
     * @version 1.1 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @param Connection $connection
     * @param integer $id
     */
    static public function findOneById(Connection $connection, $id) {
        $data = $connection->fetchAssoc('SELECT * FROM Drink WHERE id = ?', array($id));

        if (!$data) {
            return null;
        }

        $oDrink = new self($connection);

        $oDrink
            ->setId($data['id'])
            ->setPlace($data['place'])
            ->setAddress($data['address'])
            ->setDay($data['day'])
            ->setHour($data['hour'])
            ->setKind($data['kind'])
            ->setDescription($data['description'])
            ->setLatitude($data['latitude'])
            ->setLongitude($data['longitude'])
            ->setCityId($data['city_id'])
            ->setUserId($data['user_id']);

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
     * @version 1.1 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function insert()
    {
        $stmt = $this->connection->insert('Drink', array(
            'place'       => $this->place,
            'address'     => $this->address,
            'day'         => $this->day,
            'hour'        => $this->hour,
            'kind'        => $this->kind,
            'description' => $this->description,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'user_id'     => $this->userId,
            'city_id'     => $this->cityId,
        ));

        $this->id = $this->connection->lastInsertId();

        return 1 === $stmt;
    }

    /**
     * Update.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 6 févr. 2012
     * @version 1.1 - 18 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    protected function update()
    {
        return 1 === $this->connection->update('Drink', array(
            'place'       => $this->place,
            'address'     => $this->address,
            'day'         => $this->day,
            'hour'        => $this->hour,
            'kind'        => $this->kind,
            'description' => $this->description,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'user_id'     => $this->userId,
            'city_id'     => $this->cityId,
        ), array(
            'id' => $this->id
        ));
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
        if (!$this->city) {
            $this->city = City::findOneById($this->connection, $this->cityId);
        }

        return $this->city;
    }

    /**
     * Get member associated
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 8 févr. 2012
     * @version 1.0 - 8 févr. 2012 - Koin <pkoin.koin@gmail.com>
     * @return Ambigous <boolean, \Aperophp\Model\City>
     */
    public function getMember()
    {
        if (!$this->member) {
            $this->member = Member::findOneById($this->connection, $this->userId);
        }

        return $this->member;
    }

    /**
     * Get comments associated.
     *
     * @author Koin <pkoin.koin@gmail.com>
     * @since 19 févr. 2012
     * @version 1.0 - 19 févr. 2012 - Koin <pkoin.koin@gmail.com>
     */
    public function getComments()
    {
        if (!$this->comments) {
            $this->comments = DrinkComment::findByDrinkId($this->connection, $this->id);
        }

        return $this->comments;
    }

    /**
     * Get participations associated.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 21 févr. 2012
     * @version 1.0 - 21 févr. 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     */
    public function getParticipations()
    {
        if (!$this->participations) {
            $this->participations = DrinkParticipation::findByDrinkId($this->connection, $this->id);
        }

        return $this->participations;
    }

    /**
     * Get the number of participants.
     *
     * @author Gautier DI FOLCO <gautier.difolco@gmail.com>
     * @since 20 mars 2012
     * @version 1.0 - 20 mars 2012 - Gautier DI FOLCO <gautier.difolco@gmail.com>
     */
    public function getNbParticipations()
    {
        return count($this->getParticipations());
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function getAddress()
    {
        return $this->address;
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

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getCityId()
    {
        return $this->cityId;
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

    public function setAddress($address)
    {
        $this->address = $address;

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

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    public function setCityId($cityId)
    {
        $this->cityId = $cityId;

        return $this;
    }
}
