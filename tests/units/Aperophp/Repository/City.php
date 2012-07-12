<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class City extends Test
{
    public function testFindAllInAssociativeArray()
    {
        $this->assert
            ->if($cities = $this->app['cities']->findAllInAssociativeArray())
            ->then
                ->boolean(is_array($cities))->isTrue()
                ->integer(count($cities))->isEqualTo(6)
        ;
    }
}
