<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Drink extends Test
{
    public function testFindOneById_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($drink = \Aperophp\Model\Drink::findOneById($this->app['db'], 1))
            ->then
                ->boolean(is_object($drink))->isTrue()
                ->boolean($drink instanceof \Aperophp\Model\Drink)->isTrue()
        ;
    }

    public function testFindOneById_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($drink = \Aperophp\Model\Drink::findOneById($this->app['db'], 298))
            ->then
                ->boolean(null === $drink)->isTrue()
        ;
    }

    public function testFindAll_withoutLimit_returnAll()
    {
        $this->assert
            ->if($drinks = \Aperophp\Model\Drink::findAll($this->app['db']))
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(1)
        ;

        foreach ($drinks as $drink) {
            $this->assert
                ->boolean(is_object($drink))->isTrue()
                ->boolean($drink instanceof \Aperophp\Model\Drink)->isTrue()
            ;
        }
    }

    public function testFindAllJoinParticipants_withoutLimit_returnAllWithParticipants()
    {
        $this->assert
            ->if($drinks = \Aperophp\Model\Drink::findAllJoinParticipants($this->app['db']))
            ->then
                ->boolean(is_array($drinks))->isTrue()
                ->integer(count($drinks))->isEqualTo(1)
        ;

        foreach ($drinks as $drink) {
            $this->assert
                ->boolean(is_object($drink))->isTrue()
                ->boolean($drink instanceof \Aperophp\Model\Drink)->isTrue()
                ->boolean(is_array($drink->getParticipations()))->isTrue()
            ;
            foreach ($drink->getParticipations() as $participation) {
                $this->assert
                    ->boolean(is_object($participation))->isTrue()
                    ->boolean($participation instanceof \Aperophp\Model\DrinkParticipation)->isTrue()
                ;
            }
        }
    }

    public function testSaveNewDrink_withValidObject_returnTrue()
    {
        $drink = new \Aperophp\Model\Drink($this->app['db']);
        $drink->setPlace('Au père tranquille');
        $drink->setAddress('16 rue Pierre de Lescot, Paris, France');
        $drink->setDay('2012-08-23');
        $drink->setHour('19:00:00');
        $drink->setKind(\Aperophp\Model\Drink::KIND_DRINK);
        $drink->setDescription('Nouvel apéro au père tranquille');
        $drink->setLatitude('48.862140');
        $drink->setLongitude('2.348430');
        $drink->setUserId(3);
        $drink->setCityId(1);

        $this->assert
            ->boolean($drink->save())->isTrue()
        ;
    }

    public function testSaveExistingDrink_withValidObject_returnTrue()
    {
        $drink = \Aperophp\Model\Drink::findOneById($this->app['db'], 1);
        $drink->setDescription('Updated description');

        $this->assert
            ->boolean($drink->save())->isTrue()
        ;
    }
}
