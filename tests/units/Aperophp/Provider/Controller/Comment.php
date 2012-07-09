<?php

namespace tests\units\Aperophp\Provider\Controller;

require_once __DIR__.'/../../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Comment extends Test
{
    public function testCommentDrinkWithUnanonymousUser()
    {
        $this->assert
            ->if($client = $this->createClient())
            ->then
                ->if($crawler = $client->request('GET', '/drink/1/view.html'))
                ->then()
                    ->boolean($client->getResponse()->isOk())->isTrue()
                    ->integer($crawler->filter('blockquote.pull-right')->count())->isEqualTo(2)
                        ->if($form = $crawler->selectButton('comment')->form())
                        ->then()
                            ->if($crawler = $client->submit($form, array(
                                'drink_comment[user][firstname]' => 'Foo',
                                'drink_comment[user][lastname]'  => 'Bar',
                                'drink_comment[user][email]'     => 'foobar@example.org',
                                'drink_comment[content]'         => 'Super apéro.',
                            )))
                            ->then()
                                ->boolean($client->getResponse()->isRedirect('/drink/1/view.html'))->isTrue()
                                ->if($crawler = $client->followRedirect())
                                ->then()
                                    ->boolean($client->getResponse()->isOk())->isTrue()
                                    ->integer($crawler->filter('div.alert-success')->count())->isEqualTo(1)
                                    ->integer($crawler->filter('blockquote.pull-right')->count())->isEqualTo(3)
        ;
    }
}