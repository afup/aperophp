<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class User extends Test
{
    public function testFindOneByMemberId_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByMemberId(1))
            ->then
                ->boolean(is_array($user))->isTrue()
        ;
    }

    public function testFindOneByUserId_withInexistingEntry_returnEmptyArray()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByMemberId(1111))
            ->then
                ->boolean($user)->isFalse()
        ;
    }

    public function testFindOneByEmailToken_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByEmailToken('user1@example.org', 'token'))
            ->then
                ->boolean(is_array($user))->isTrue()
        ;
    }

    public function testFindOneByEmailToken_withInexistingEntry_returnIt()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByEmailToken('user1@example.org', 'wrong-token'))
            ->then
                ->boolean($user)->isFalse()
        ;
    }

    public function testUpdate()
    {
        $this->assert
            ->if($user = $this->app['users']->find(1))
            ->then
                ->boolean(is_array($user))->isTrue()
                ->string($user['firstname'])->isEqualTo('User1')
                ->string($user['lastname'])->isEqualTo('Example1')
        ;

        $user['firstname'] = 'foo';
        $user['lastname'] = 'bar';

        $this->assert
            ->integer($this->app['users']->update($user, array('id' => 1)))->isEqualTo(1)
        ;

        $this->assert
            ->if($user = $this->app['users']->find(1))
            ->then
                ->boolean(is_array($user))->isTrue()
                ->string($user['firstname'])->isEqualTo('foo')
                ->string($user['lastname'])->isEqualTo('bar')
        ;
    }

    public function testFindOneByEmail_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByEmail('user1@example.org'))
            ->then
                ->boolean(is_array($user))->isTrue()
        ;
    }

    public function testFindOneByEmail_withInexistingEntry_returnIt()
    {
        $this->assert
            ->if($user = $this->app['users']->findOneByEmail('user42@example.org'))
            ->then
                ->boolean($user)->isFalse()
        ;
    }
}
