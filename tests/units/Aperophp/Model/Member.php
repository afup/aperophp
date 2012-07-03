<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Member extends Test
{
    public function testFindOneByUsername_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($member = \Aperophp\Model\Member::findOneByUsername($this->app['db'], 'user'))
            ->then
                ->boolean(is_object($member))->isTrue()
                ->boolean($member instanceof \Aperophp\Model\Member)->isTrue()
        ;
    }

    public function testFindOneByUsername_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($member = \Aperophp\Model\Member::findOneByUsername($this->app['db'], 'unknow-user'))
            ->then
                ->boolean(null === $member)->isTrue()
        ;
    }

    public function testFindOneById_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($member = \Aperophp\Model\Member::findOneById($this->app['db'], 1))
            ->then
                ->boolean(is_object($member))->isTrue()
                ->boolean($member instanceof \Aperophp\Model\Member)->isTrue()
        ;
    }

    public function testFindOneById_withInexistingEntry_returnNull()
    {
        $this->assert
            ->if($member = \Aperophp\Model\Member::findOneById($this->app['db'], 298))
            ->then
                ->boolean(null === $member)->isTrue()
        ;
    }

    public function testSaveNewMember_withValidObject_returnTrue()
    {
        $member = new \Aperophp\Model\Member($this->app['db']);
        $member->setUsername('new-user');
        $member->setPassword('password');
        $member->setActive(true);

        $this->assert
            ->boolean($member->save())->isTrue()
        ;
    }

    public function testSaveExistingMember_withValidObject_returnTrue()
    {
        $member = \Aperophp\Model\Member::findOneById($this->app['db'], 1);
        $member->setPassword('new-password');

        $this->assert
            ->boolean($member->save())->isTrue()
        ;
    }
}
