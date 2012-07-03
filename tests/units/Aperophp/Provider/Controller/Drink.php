<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Drink extends Test
{
    public function testDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/drink/'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
        ;
    }
}
