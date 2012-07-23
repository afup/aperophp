<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class DrinkParticipant extends Test
{
    public function testFindOne_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findOne(1, 1))
            ->then
                ->boolean(is_array($participation))->isTrue()
        ;
    }

    public function testFindOne_withInexistingEntry_returnFalse()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findOne(231, 2341))
            ->then
                ->boolean($participation)->isFalse()
        ;
    }

    public function testFindByDrinkId_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findByDrinkId(1))
            ->then
                ->if(is_array($participation))
                ->then
                    ->boolean(2 == count($participation))->isTrue()
        ;
    }

    public function testFindByDrinkId_withUknownEntry_returnArray()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findByDrinkId(42))
            ->then
                ->if(is_array($participation))
                ->then
                    ->boolean(0 == count($participation))->isTrue()
        ;
    }

    public function testFindByUserId_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findDrinksByUserId(1))
            ->then
                ->if(is_array($participation))
                ->then
                    ->boolean(1 == count($participation))->isTrue()
        ;
    }

    public function testFindByUserId_withUknownEntry_returnArray()
    {
        $this->assert
            ->if($participation = $this->app['drink_participants']->findDrinksByUserId(42))
            ->then
                ->if(is_array($participation))
                ->then
                    ->boolean(0 == count($participation))->isTrue()
        ;
    }
}
