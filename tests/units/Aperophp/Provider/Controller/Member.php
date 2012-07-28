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

    public function testSignup_connected()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                    ->then()
                    ->if($crawler = $client->request('GET', '/member/signup.html'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
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
                                ->boolean($client->getResponse()->isRedirect('/member/edit.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testEditProfile_withInvalidData_isModified()
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
                                'member_edit[user][lastname]'   => '',
                                'member_edit[user][firstname]'  => '',
                                'member_edit[user][email]'      => '',
                                'member_edit[member][password]' => '',
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/member/edit.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
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

    public function testSignin_badPassword()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/signin.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('login')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'signin[username]'   => 'user2',
                            'signin[password]'   => 'badpassword',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect())->isFalse()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testSignin_connected()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                    ->then()
                    ->if($crawler = $client->request('GET', '/member/signin.html'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testForget_goodMail()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'forget[email]' => 'user3@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                            ->if($crawler = $client->followRedirect())
                            ->then()
                                ->boolean($client->getResponse()->isOk())->isTrue()
                                ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                ->boolean(true == $client->connect())->isTrue()
        ;
    }

    public function testForget_badMail()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'forget[email]' => 'user42@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/member/forget.html'))->isTrue()
                            ->if($crawler = $client->followRedirect())
                            ->then()
                                ->boolean($client->getResponse()->isOk())->isTrue()
                                ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testForget_user()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'forget[email]' => 'user2@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/member/forget.html'))->isTrue()
                            ->if($crawler = $client->followRedirect())
                            ->then()
                                ->boolean($client->getResponse()->isOk())->isTrue()
                                ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testForget_connected()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                    ->then()
                    ->if($crawler = $client->request('GET', '/member/forget.html'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testRemember_goodData()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/remember.html/user3@example.org/token'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/member/signin.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testRemember_badToken()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/remember.html/user3@example.org/token42'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testRemember_badEmail()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/remember.html/user42@example.org/token'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testRemember_anonymous()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/member/remember.html/user2@example.org/token'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testRemember_connected()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true == $client->connect())
                    ->then()
                    ->if($crawler = $client->request('GET', '/member/remember.html/user@example.org/token'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }
}
