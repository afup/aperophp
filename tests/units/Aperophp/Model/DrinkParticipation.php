<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class DrinkParticipation extends Test
{
    public function testFind_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($drinkParticipation = \Aperophp\Model\DrinkParticipation::find($this->app['db'], 1, 1))
            ->then
                ->boolean(is_object($drinkParticipation))->isTrue()
                ->boolean($drinkParticipation instanceof \Aperophp\Model\DrinkParticipation)->isTrue()
        ;
    }

    public function testFind_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($drinkParticipation = \Aperophp\Model\DrinkParticipation::find($this->app['db'], 2, 2))
            ->then
                ->boolean(null === $drinkParticipation)->isTrue()
        ;
    }

    public function testFindByDrinkId_withExistingEntries_returnIt()
    {
        $this->assert
            ->if($drinkParticipations = \Aperophp\Model\DrinkParticipation::findByDrinkId($this->app['db'], 1))
            ->then
                ->boolean(is_array($drinkParticipations))->isTrue()
                ->integer(count($drinkParticipations))->isEqualTo(2)
        ;
    }

    public function testFindByDrinkId_withInexistingEntries_returnEmptyArray()
    {
        $this->assert
            ->if($drinkParticipations = \Aperophp\Model\DrinkParticipation::findByDrinkId($this->app['db'], 3))
            ->then
                ->boolean(is_array($drinkParticipations))->isTrue()
                ->integer(count($drinkParticipations))->isEqualTo(0)
        ;
    }

    public function testFindByUserId_withExistingEntries_returnIt()
    {
        $this->assert
            ->if($drinkParticipations = \Aperophp\Model\DrinkParticipation::findByUserId($this->app['db'], 1))
            ->then
                ->boolean(is_array($drinkParticipations))->isTrue()
                ->integer(count($drinkParticipations))->isEqualTo(1)
        ;
    }

    public function testFindByUserId_withInexistingEntries_returnEmptyArray()
    {
        $this->assert
            ->if($drinkParticipations = \Aperophp\Model\DrinkParticipation::findByUserId($this->app['db'], 2))
            ->then
                ->boolean(is_array($drinkParticipations))->isTrue()
                ->integer(count($drinkParticipations))->isEqualTo(0)
        ;
    }

    public function testSaveNewDrinkParticipation_withValidObject_returnTrue()
    {
        $drinkParticipation = new \Aperophp\Model\DrinkParticipation($this->app['db']);
        $drinkParticipation->setDrinkId(1);
        $drinkParticipation->setUserId(2);
        $drinkParticipation->setPercentage(75);

        $this->assert
            ->boolean($drinkParticipation->save())->isTrue()
        ;
    }

    public function testSaveExistingDrinkParticipation_withValidObject_returnTrue()
    {
        $drinkParticipation = \Aperophp\Model\DrinkParticipation::find($this->app['db'], 1, 1);
        $drinkParticipation->setPercentage(100);

        $this->assert
            ->boolean($drinkParticipation->save())->isTrue()
        ;
    }

    public function testDeleteDrinkParticipation_withValidObject_returnTrue()
    {
        $drinkParticipation = \Aperophp\Model\DrinkParticipation::find($this->app['db'], 1, 1);

        $this->assert
            ->boolean($drinkParticipation->delete())->isTrue()
        ;
    }
}
