<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class User extends Test
{
    public function testFindOneById_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneById($this->app['db'], 1))
            ->then
                ->boolean(is_object($user))->isTrue()
                ->boolean($user instanceof \Aperophp\Model\User)->isTrue()
        ;
    }

    public function testFindOneById_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneById($this->app['db'], 2765))
            ->then
                ->boolean(null === $user)->isTrue()
        ;
    }

    public function testFindOneByMemberId_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneByMemberId($this->app['db'], 1))
            ->then
                ->boolean(is_object($user))->isTrue()
                ->boolean($user instanceof \Aperophp\Model\User)->isTrue()
        ;
    }

    public function testFindOneByUserId_withInexistingEntry_returnEmptyArray()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneBymemberId($this->app['db'], 376))
            ->then
                ->boolean(null === $user)->isTrue()
        ;
    }

    public function testFindOneByEmailToken_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneByEmailToken($this->app['db'], 'user1@example.org', 'token'))
            ->then
                ->boolean(is_object($user))->isTrue()
                ->boolean($user instanceof \Aperophp\Model\User)->isTrue()
        ;
    }

    public function testFindOneByEmailToken_withInexistingEntry_returnIt()
    {
        $this->assert
            ->if($user = \Aperophp\Model\User::findOneByEmailToken($this->app['db'], 'user@example.org', 'wrong-token'))
            ->then
                ->boolean(null === $user)->isTrue()
        ;
    }

    public function testSaveNewUser_withValidObject_returnTrue()
    {
        $user = new \Aperophp\Model\User($this->app['db']);
        $user->setLastname('bar');
        $user->setFirstname('foo');
        $user->setEmail('foobar@example.org');
        $user->setToken('token');

        $this->assert
            ->boolean($user->save())->isTrue()
        ;
    }

    public function testSaveExistingUser_withValidObject_returnTrue()
    {
        $user = \Aperophp\Model\User::findOneById($this->app['db'], 1);
        $user->setEmail('foobar@example.org');

        $this->assert
            ->boolean($user->save())->isTrue()
        ;
    }
}
