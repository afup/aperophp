<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Member extends Test
{
    public function testFindOneByUsernameAndPassword_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsernameAndPassword('user', $this->app['utils']->hash('password')))
            ->then
                ->boolean(is_array($member))->isTrue()
        ;
    }

    public function testFindOneByUsernameAndPassword_withIncorrectPassword_returnFalse()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsernameAndPassword('user', 'wrong-password'))
            ->then
                ->boolean($member)->isFalse()
        ;
    }

    public function testFindOneByUsernameAndPassword_withInactiveUser_returnFalse()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsernameAndPassword('inactive-user', $this->app['utils']->hash('password')))
            ->then
                ->boolean($member)->isFalse()
        ;
    }

    public function testFindOneByUsername_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsername('user'))
            ->then
                ->boolean(is_array($member))->isTrue()
        ;
    }

    public function testFindOneByUsername_withIncorrectUsername_returnFalse()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsername('no-exist-user'))
            ->then
                ->boolean($member)->isFalse()
        ;
    }

    public function testFindOneByUsername_withInactiveUser_returnArray()
    {
        $this->assert
            ->if($member = $this->app['members']->findOneByUsername('inactive_user'))
            ->then
                ->boolean(is_array($member))->isTrue()
        ;
    }
}
