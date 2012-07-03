<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class City extends Test
{
    public function testFindOneById_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($city = \Aperophp\Model\City::findOneById($this->app['db'], 1))
            ->then
                ->boolean(is_object($city))->isTrue()
                ->boolean($city instanceof \Aperophp\Model\City)->isTrue()
        ;
    }

    public function testFindOneById_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($city = \Aperophp\Model\City::findOneById($this->app['db'], 298))
            ->then
                ->boolean(null === $city)->isTrue()
        ;
    }

    public function testFindAll()
    {
        $this->assert
            ->if($cities = \Aperophp\Model\City::findAll($this->app['db']))
            ->then
                ->boolean(is_array($cities))->isTrue()
                ->integer(count($cities))->isEqualTo(6)
        ;
    }
}
