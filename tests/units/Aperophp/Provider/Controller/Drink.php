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
                ->if($crawler = $client->request('GET', '/'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
        ;
    }

    public function testNewDrink_withValidData_isCreated()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true === $client->connect())
                ->then()
                    ->if($crawler = $client->request('GET', '/new.html'))
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->if($form = $crawler->selectButton('create')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink[hour]'        => '19:30:00',
                                'drink[description]' => 'ApéroPHP au père tranquille.',
                                'drink[city_id]'     => '5',
                                'drink[place]'       => 'Au père tranquille',
                                'drink[address]'     => '16 Rue Pierre Lescot, Paris, France',
                                'drink[latitude]'    => '48.86214',
                                'drink[longitude]'   => '2.34843',
                                'drink[day]'         => '2012-07-19',
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/3/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testNewDrink_withAnonymousUser_isRedirectedToLoginform()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/new.html'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testEditDrink_withValidData_isUpdated()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                ->then()
                    ->if($crawler = $client->request('GET', '/1/edit.html'))
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->if($form = $crawler->selectButton('update')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink[hour]'        => '20:00:00',
                                'drink[description]' => 'ApéroPHP au père tranquille [Edited].',
                                'drink[city_id]'     => '5',
                                'drink[place]'       => 'Au père tranquille',
                                'drink[address]'     => '16 Rue Pierre Lescot, Paris, France',
                                'drink[latitude]'    => '48.86214',
                                'drink[longitude]'   => '2.34843',
                                'drink[day]'         => '2012-07-20',
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                    ->integer($crawler->filter('div.alert-success:contains("apéro a été modifié avec succès")')->count())->isEqualTo(1)
        ;
    }

    public function testEditDrink_withAnonymousUser_isRedirectedToLoginForm()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/1/edit.html'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testEditDrink_withWrongUser_isRedirectedToLoginForm()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect('user2', 'password'))
                ->then
                    ->if($crawler = $client->request('GET', '/1/edit.html'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                            ->if($crawler = $client->followRedirect())
                            ->then()
                                ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testEditDrink_withOldDrink_isRedirectedToDrinkView()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/2/edit.html'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/2/view.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testEditDrink_withUnknowDrink_isRedirectedTo404()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/1456/edit.html'))
                ->then()
                    ->boolean($client->getResponse()->isNotFound())->isTrue()
        ;
    }

    public function testDrinkList()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/list.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
        ;
    }

    public function testViewDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/1/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
        ;
    }

    public function testViewUnexistentDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/987/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isNotFound())->isTrue()
        ;
    }
}
