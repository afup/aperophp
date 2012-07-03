<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Member extends Test
{
    public function testSignup_withValidData_isRegistered()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/signup.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('register')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'signup[lastname]'  => 'Foo',
                            'signup[firstname]' => 'Bar',
                            'signup[username]'  => 'foobar',
                            'signup[email]'     => 'foobar@example.com',
                            'signup[password]'  => 'foobar',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                            ->if($crawler = $client->followRedirect())
                            ->then()
                                ->boolean($client->getResponse()->isOk())->isTrue()
                                ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testSignup_withIncorrectData_formIsDisplayedWithErrors()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/signup.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('register')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'signup[lastname]'  => '',
                            'signup[firstname]' => '',
                            'signup[username]'  => '',
                            'signup[email]'     => 'foobar',
                            'signup[password]'  => 'f',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect())->isFalse()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
                            ->integer($crawler->filter('span.help-inline')->count())->isEqualTo(3)
        ;
    }
}
