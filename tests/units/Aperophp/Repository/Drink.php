<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Drink extends Test
{
    public function testFindAllNull()
    {
        $this->assert
            ->if($drinks = $this->app['drinks']->findAll())
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(2)
            ;

        foreach ($drinks as $drink) {
            $this->assert
                ->boolean(is_array($drink))->isTrue()
                ->boolean(array_key_exists('participants_count', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_username', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_email', $drink))->isTrue()
                ->boolean(array_key_exists('city_name', $drink))->isTrue()
            ;
        }
    }

    public function testFindAll()
    {
        $this->assert
            ->if($drinks = $this->app['drinks']->findAll(3))
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(2)
            ;

        foreach ($drinks as $drink) {
            $this->assert
                ->boolean(is_array($drink))->isTrue()
                ->boolean(array_key_exists('participants_count', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_username', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_email', $drink))->isTrue()
                ->boolean(array_key_exists('city_name', $drink))->isTrue()
            ;
        }
    }

    public function testFindNext()
    {
        $this->assert
            ->if($drinks = $this->app['drinks']->findNext())
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(1)
            ;

        foreach ($drinks as $drink) {
            $this->assert
                ->boolean(is_array($drink))->isTrue()
                ->boolean(array_key_exists('participants_count', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_username', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_email', $drink))->isTrue()
                ->boolean(array_key_exists('city_name', $drink))->isTrue()
            ;
        }
    }

    public function testFind_withExistingDrink_returnArray()
    {
        $this->assert
            ->if($drink = $this->app['drinks']->find(1))
            ->then
                ->boolean(is_array($drink))->isTrue()
                ->boolean(is_array($drink))->isTrue()
                ->boolean(array_key_exists('participants_count', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_username', $drink))->isTrue()
                ->boolean(array_key_exists('organizer_email', $drink))->isTrue()
                ->boolean(array_key_exists('city_name', $drink))->isTrue()
            ;
    }

    public function testFind_withInexistingDrink_returnFalse()
    {
        $this->assert
            ->if($drink = $this->app['drinks']->find(13984))
            ->then
                ->boolean($drink)->isFalse()
            ;
    }

    public function testFindAllKindsInAssociativeArray()
    {
        $this->assert
            ->if($drinks = $this->app['drinks']->findAllKindsInAssociativeArray())
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(2)
        ;
    }
}
