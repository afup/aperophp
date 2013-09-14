<?php

namespace Aperophp\Repository;

use Aperophp\Lib\Stats;

class City extends Repository
{

    const ALL = 0;

    public function getTableName()
    {
        return 'City';
    }

    /**
     * findAllInAssociativeArray
     *
     * @return array
     */
    public function findAllInAssociativeArray()
    {
        $cities = array();
        foreach ($this->findAll() as $city) {
            $cities[$city['id']] = $city['name'];
        }

        return $cities;
    }

    /**
     *
     * @return array
     */
    public function findRecurrentInAssociativeArray()
    {
        $cities = array();
      $sql = sprintf(
        'SELECT c.id as id, c.name as name
           FROM Drink d, City c
          WHERE d.city_id = c.id
          GROUP BY c.id
          HAVING COUNT(d.id) > %s
          ORDER BY name
        ', Stats::RECURRENT_MINIMUM);

        foreach ($this->db->fetchAll($sql) as $city) {
            $cities[$city['id']] = $city['name'];
        }

        return $cities;
    }

}
