<?php

namespace Aperophp\Lib;

use Doctrine\DBAL\Connection;
use Aperophp\Repository\City;
use Aperophp\Repository\Drink;

class Stats
{
    /**
     *
     */
    const RECURRENT_MINIMUM = 5;

    /**
     * @var string|null
     */
    protected $dateFrom;

    /**
     * @var int
     */
    protected $city;

    /**
     * @param Connection $db
     * @param string|null $dateFrom
     * @param int $city
     */
    public function __construct(Connection $db, $dateFrom = null, $city = City::ALL)
    {
      $this->db = $db;
      $this->dateFrom = $dateFrom;
      $this->city = $city;
    }

    /**
     * @param string $type
     *
     * @return null|string
     */
    public static function getDateFrom($type)
    {
      $dateFrom = null;
      $date = new \DateTime();
      if ($type == 'year') {
        $date->modify('-1 year');
        $dateFrom = $date->format('Y-m-d');
       } elseif ($type == 'month') {
        $date->modify('-6 month');
         $dateFrom = $date->format('Y-m-d');
       }

      return $dateFrom;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
      return array(
        'all' => 'Toutes',
        'year' => 'Depuis un an',
        'month' => 'Depuis 6 mois',
      );
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    protected function getBaseDrinkQuery()
    {
      $dateFrom = $this->dateFrom;
      $city = $this->city;
      $queryBuilder = $this->db->createQueryBuilder()
        ->from('Drink', 'd')
      ;
      if (null !== $dateFrom) {
        $queryBuilder->andWhere('day > :datefrom');
        $queryBuilder->setParameter('datefrom', $dateFrom);
      }
      if ($city != City::ALL) {
       $queryBuilder->andWhere(sprintf('city_id = %s', $city));
      }

      return $queryBuilder;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        $queryBuilder = $this->getBaseDrinkQuery()
          ->select('count(d.id) as count')
        ;

        return $queryBuilder->execute()->fetchColumn();
    }

    /**
     * @param bool $onlyRecurrentCities
     *
     * @return array
     */
    public function averageParticipantsByCity($onlyRecurrentCities = false)
    {
      $queryBuilder = $this->getBaseDrinkQuery()
        ->addSelect(sprintf("CEILING(AVG((%s))) as participants_avg", Drink::getCountParticipantsQuery()))
        ->addSelect('COUNT(d.id) as total_drinks')
        ->addSelect('c.name as name')
        ->innerJoin('d', 'City', 'c', 'd.city_id = c.id')
        ->addGroupBy('c.id')
        ->addOrderBy('participants_avg', 'DESC')
        ->addOrderBy('name')
      ;

      if ($onlyRecurrentCities) {
        $queryBuilder->andHaving('total_drinks > :recurrent_minimum');
        $queryBuilder->setParameter('recurrent_minimum', self::RECURRENT_MINIMUM);
      }

      return $queryBuilder->execute()->fetchAll();
    }

    /**
     * @return int
     */
    public function countAllParticipants()
    {
      $queryBuilder = $this->getBaseDrinkQuery()
        ->addSelect('count(*) as count')
        ->innerJoin('d', 'Drink_Participation', 'dp', 'dp.drink_id = d.id')
        ->andWhere('dp.percentage > 0')
      ;

      return $queryBuilder->execute()->fetchColumn();
    }

    /**
     * @return array
     */
    public function countParticipantsByDate()
    {
      $queryBuilder = $this->getBaseDrinkQuery()
        ->addSelect('count(*) as count')
        ->addSelect('d.day as day')
        ->innerJoin('d', 'Drink_Participation', 'dp', 'dp.drink_id = d.id')
        ->andWhere('dp.percentage > 0')
        ->addGroupBy('day')
      ;

      $dates = array();

      foreach ($queryBuilder->execute() as $row) {
        $dates[$row['day']] = $row['count'];
      }

      return $dates;
    }

    /**
     * @return array
     */
    public function getGeoInformations()
    {
      $queryBuilder = $this->getBaseDrinkQuery()
        ->addSelect('latitude', 'longitude', 'description')
        ->addGroupBy('d.id')
        ->addOrderBy('created_at', 'DESC')
      ;
      return $queryBuilder->execute()->fetchAll();
    }

    /**
     * @return array
     */
    public function findFirst()
    {
      $queryBuilder = $this->getBaseDrinkQuery()
        ->select('*')
        ->addOrderBy('day')
        ->addOrderBy('hour')
        ->addOrderBy('created_at')
        ->setMaxResults(1)
      ;

      return $queryBuilder->execute()->fetch();
    }

    /**
     * @return mixed
     */
    public function getMostUSedMonth()
    {
      return $this->getBaseDrinkQuery()
        ->addSelect('day')
        ->addOrderBy('COUNT(*)')
        ->innerJoin('d', 'Drink_Participation', 'dp', 'dp.drink_id = d.id')
        ->andWhere('dp.percentage > 0')
        ->addGroupBy('MONTH(day)')
        ->setMaxResults(1)
        ->execute()
        ->fetchColumn()
      ;
    }

    /**
     * @return int
     */
    public function getAverageParticipants()
    {
      return $this->getBaseDrinkQuery()
        ->select(sprintf("CEILING(AVG((%s))) as participants_avg", Drink::getCountParticipantsQuery()))
        ->innerJoin('d', 'Drink_Participation', 'dp', 'dp.drink_id = d.id')
        ->execute()
        ->fetchColumn()
      ;
    }

    /**
     * @return int
     */
    public function getMaxParticipants()
    {
      return $this->getBaseDrinkQuery()
        ->select(sprintf("MAX((%s)) as participants_avg", Drink::getCountParticipantsQuery()))
        ->innerJoin('d', 'Drink_Participation', 'dp', 'dp.drink_id = d.id')
        ->execute()
        ->fetchColumn()
      ;
    }
}
