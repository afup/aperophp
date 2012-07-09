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
                            'signup[user][lastname]'   => 'Foo',
                            'signup[user][firstname]'  => 'Bar',
                            'signup[member][username]' => 'foobar',
                            'signup[user][email]'      => 'foobar@example.com',
                            'signup[member][password]' => 'foobar',
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
                            'signup[user][lastname]'   => '',
                            'signup[user][firstname]'  => '',
                            'signup[member][username]' => '',
                            'signup[user][email]'      => 'foobar',
                            'signup[member][password]' => 'f',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect())->isFalse()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
                            ->integer($crawler->filter('span.help-inline')->count())->isEqualTo(3)
        ;
    }

    public function testEditProfile_withValidData_isModified()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                ->then()
                    ->if($crawler = $client->request('GET', '/member/edit.html'))
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->if($form = $crawler->selectButton('edit')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'member_edit[user][lastname]'   => 'Foo',
                                'member_edit[user][firstname]'  => 'Bar',
                                'member_edit[user][email]'      => 'foobar@example.com',
                                'member_edit[member][password]' => '',
                            )))
                            ->then()
                                //->string($client->getResponse()->getContent())->isEqualTo('toto')
                                ->boolean($client->getResponse()->isRedirect('/member/edit.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testEditProfile_withAnonymousUser_isRedirectedToLoginform()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/edit.html'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }
}
