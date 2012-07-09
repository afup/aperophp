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
}
