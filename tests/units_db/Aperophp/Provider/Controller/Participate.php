<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Participate extends Test
{
    public function testParticipateToDrink_withRegisteredMember_participationSaved()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if(true === $client->connect())
                ->then
                    ->if($crawler = $client->request('GET', '/1/view.html'))
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(1)
                        ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(1)
                            ->if($form = $crawler->selectButton('participate')->form())
                            ->then()
                                ->if($crawler = $client->submit($form, array(
                                    'drink_participate[percentage]'      => '30',
                                    'drink_participate[reminder]'        => true,
                                )))
                                ->then()
                                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                    ->if($crawler = $client->followRedirect())
                                    ->then()
                                        ->boolean($client->getResponse()->isOk())->isTrue()
                                        ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                        ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(1)
                                        ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(1)
                                        // Now, I participate to the drink.
                                        // CHeck if all fields have been prefilled
                                        ->string($crawler->filter('select#drink_participate_percentage option[selected=selected]')->first()->attr('value'))->isEqualTo('30')
                                        ->string($crawler->filter('input#drink_participate_reminder')->first()->attr('value'))->isEqualTo(1)
                                        // Submit the form again to modified participation
                                        ->if($crawler = $client->submit($form, array(
                                            'drink_participate[percentage]'      => '70',
                                            'drink_participate[reminder]'        => true,
                                        )))
                                        ->then()
                                            ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                            ->if($crawler = $client->followRedirect())
                                            ->then()
                                                ->boolean($client->getResponse()->isOk())->isTrue()
                                                ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                                ->string($crawler->filter('select#drink_participate_percentage option[selected=selected]')->first()->attr('value'))->isEqualTo('70')
                                                ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(1)
                                                ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(1)
                                                // Delete an existing participation
                                                ->if($crawler = $client->request('GET', '/participation/1/delete.html'))
                                                ->then()
                                                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                                    ->if($crawler = $client->followRedirect())
                                                    ->then()
                                                        ->boolean($client->getResponse()->isOk())->isTrue()
                                                        ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                                        ->integer($crawler->filter('div.participation a.btn:contains("S\'inscrire")')->count())->isEqualTo(1)
                                                        ->integer($crawler->filter('div.participation a.btn:contains("Jeton perdu ?")')->count())->isEqualTo(0)
                                                        // Delete a non-existing participation
                                                        ->if($crawler = $client->request('GET', '/participation/1/delete.html'))
                                                        ->then()
                                                            ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                                            ->if($crawler = $client->followRedirect())
                                                            ->then()
                                                                ->boolean($client->getResponse()->isOk())->isTrue()
                                                                ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testEditParticipationToADrink_withUnanonymousUser_participationSaved()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/1/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->integer($crawler->filter('div.participation a.btn:contains("S\'inscrire")')->count())->isEqualTo(1)
                    ->integer($crawler->filter('div.participation a.btn:contains("Jeton perdu ?")')->count())->isEqualTo(1)
                        ->if($form = $crawler->selectButton('participate')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink_participate[user][firstname]' => 'Foo',
                                'drink_participate[user][lastname]'  => 'Bar',
                                'drink_participate[user][email]'     => 'foobar@example.org',
                                'drink_participate[percentage]'      => '30',
                                'drink_participate[reminder]'        => true,
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                    // Now, I participate to the drink.
                                    ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(1)
                                    ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(1)
                                    // CHeck if all fields have been prefilled
                                    ->string($crawler->filter('input#drink_participate_user_firstname')->first()->attr('value'))->isEqualTo('Foo')
                                    ->string($crawler->filter('input#drink_participate_user_lastname')->first()->attr('value'))->isEqualTo('Bar')
                                    ->string($crawler->filter('input#drink_participate_user_email')->first()->attr('value'))->isEqualTo('foobar@example.org')
                                    ->string($crawler->filter('select#drink_participate_percentage option[selected=selected]')->first()->attr('value'))->isEqualTo('30')
                                    ->string($crawler->filter('input#drink_participate_reminder')->first()->attr('value'))->isEqualTo(1)
                                    // Submit the form again to modified participation
                                    ->if($crawler = $client->submit($form, array(
                                        'drink_participate[user][firstname]' => 'Foo',
                                        'drink_participate[user][lastname]'  => 'Bar',
                                        'drink_participate[user][email]'     => 'foobar@example.org',
                                        'drink_participate[percentage]'      => '70',
                                        'drink_participate[reminder]'        => true,
                                    )))
                                    ->then()
                                        ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                        ->if($crawler = $client->followRedirect())
                                        ->then()
                                            ->boolean($client->getResponse()->isOk())->isTrue()
                                            ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                            ->string($crawler->filter('select#drink_participate_percentage option[selected=selected]')->first()->attr('value'))->isEqualTo('70')
                                            ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(1)
                                            ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(1)
                                            // Delete an existing participation
                                            ->if($crawler = $client->request('GET', '/participation/1/delete.html'))
                                            ->then()
                                                ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                                ->if($crawler = $client->followRedirect())
                                                ->then()
                                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                                    ->integer($crawler->filter('div.participation a.btn:contains("Modifier")')->count())->isEqualTo(0)
                                                    ->integer($crawler->filter('div.participation a.btn:contains("Se désinscrire")')->count())->isEqualTo(0)
                                                    ->integer($crawler->filter('div.participation a.btn:contains("S\'inscrire")')->count())->isEqualTo(1)
                                                    ->integer($crawler->filter('div.participation a.btn:contains("Jeton perdu ?")')->count())->isEqualTo(0)
                                                    // Delete a non-existing participation
                                                    ->if($crawler = $client->request('GET', '/participation/1/delete.html'))
                                                    ->then()
                                                        ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                                        ->if($crawler = $client->followRedirect())
                                                        ->then()
                                                            ->boolean($client->getResponse()->isOk())->isTrue()
                                                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;

    }

    public function testDelete_withUnknowDrink_isRedirectedTo404()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/42/delete.html'))
                ->then()
                    ->boolean($client->getResponse()->isNotFound())->isTrue()
                    ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withFinishedDrink_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/2/delete.html'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/2/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withNoUser_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/2/delete.html/marvin/42'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/2/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withUnknownUser_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/delete.html/marvin/42'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withNoUserToken_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/delete.html/user1@example.org'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withBadUserToken_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/delete.html/user1@example.org/42'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testParticipateToADrink_withUnanonymousUser_badData()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/1/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                        ->if($form = $crawler->selectButton('participate')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink_participate[user][firstname]' => '',
                                'drink_participate[user][lastname]'  => '',
                                'drink_participate[user][email]'     => '',
                                'drink_participate[percentage]'      => 0,
                                'drink_participate[reminder]'        => true,
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testDelete_withGoodUserToken_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/delete.html/user1@example.org/token'))
                ->then()
                    ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                    ->if($crawler = $client->followRedirect())
                    ->then()
                        ->boolean($client->getResponse()->isOk())->isTrue()
                        ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
        ;
    }

    public function testForget_goodMail()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'participation_forget[email]' => 'user3@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
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
                ->if($crawler = $client->request('GET', '/participation/1/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'participation_forget[email]' => 'user42@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/participation/1/forget.html'))->isTrue()
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
                ->if($crawler = $client->request('GET', '/participation/1/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'participation_forget[email]' => 'user2@example.org',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isRedirect('/participation/1/forget.html'))->isTrue()
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
                    ->if($crawler = $client->request('GET', '/participation/1/forget.html'))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testForget_withunknownDrink_isRedirectedToDrink()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/42/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isNotFound())->isTrue()
                    ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }

    public function testForget_noData()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/participation/1/forget.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->if($form = $crawler->selectButton('remember')->form())
                    ->then()
                        ->if($crawler = $client->submit($form, array(
                            'participation_forget[email]' => '',
                        )))
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-error')->count())->isEqualTo(1)
        ;
    }
}
