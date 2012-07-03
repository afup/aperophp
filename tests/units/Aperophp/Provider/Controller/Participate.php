<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Participate extends Test
{
    public function testParticipateToDrinkWithUnanonymousUser()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/drink/1/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                        ->if($form = $crawler->selectButton('participate')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink_participate[firstname]'  => 'Foo',
                                'drink_participate[lastname]'   => 'Bar',
                                'drink_participate[email]'      => 'foobar@example.org',
                                'drink_participate[percentage]' => '90',
                                'drink_participate[reminder]'   => true,
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/drink/1/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }
}
