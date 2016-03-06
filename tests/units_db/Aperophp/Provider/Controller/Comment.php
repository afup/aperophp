<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Comment extends Test
{
    protected function getDefaultDatas($data_overload=array())
    {
        return array_merge(
            array(
                'drink_comment[captcha]'         => '',
                'drink_comment[user][firstname]' => 'Foo',
                'drink_comment[user][lastname]'  => 'Bar',
                'drink_comment[user][email]'     => 'foobar@example.org',
                'drink_comment[content]'         => 'Super apéro.',
            ),
            $data_overload
        );
    }

    public function testCommentDrinkWithUnanonymousUser()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->and($crawler = $client->request('GET', '/1/view.html'))
            ->then()
                ->boolean($client->getResponse()->isOk())->isTrue()
                ->integer($crawler->filter('blockquote')->count()-1)->isEqualTo(2)
                    ->if($form = $crawler->selectButton('comment')->form())
                    ->and($crawler = $client->submit($form, $this->getDefaultDatas()))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/1/view.html'))->isTrue()
                        ->if($crawler = $client->followRedirect())
                        ->then()
                            ->boolean($client->getResponse()->isOk())->isTrue()
                            ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                            ->integer($crawler->filter('blockquote')->count()-1)->isEqualTo(3)
        ;
    }

    public function testNewCommentDrink_withNoCaptcha_isNotCreated()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->and($crawler = $client->request('GET', '/1/view.html'))
            ->then()
                ->boolean($client->getResponse()->isOk())->isTrue()
                ->integer($crawler->filter('blockquote')->count()-1)->isEqualTo(2)
                    ->if($form = $crawler->selectButton('comment')->form())
                    ->and($crawler = $client->submit($form, $this->getDefaultDatas(array('drink_comment[user][firstname]' => 'DO NOT FEED'))))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/drink/1/view.html'))->isFalse()
        ;
    }

    public function testCommentDrinkWithNoData()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->and($crawler = $client->request('GET', '/1/view.html'))
            ->then()
                ->boolean($client->getResponse()->isOk())->isTrue()
                ->integer($crawler->filter('blockquote')->count()-1)->isEqualTo(2)
                    ->if($form = $crawler->selectButton('comment')->form())
                    ->and($crawler = $client->submit($form, $this->getDefaultDatas(array(
                        'drink_comment[user][firstname]' => '',
                        'drink_comment[user][lastname]'  => '',
                        'drink_comment[user][email]'     => '',
                        'drink_comment[content]'         => '',
                    ))))
                    ->then()
                        ->boolean($client->getResponse()->isRedirect('/drink/1/view.html'))->isFalse()
        ;
    }
}
