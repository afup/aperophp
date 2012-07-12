<?php

namespace Aperophp\Repository;

class City extends Repository
{
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
}
