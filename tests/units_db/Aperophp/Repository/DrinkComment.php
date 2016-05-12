<?php

namespace tests\units\Aperophp\Repository;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class DrinkComment extends Test
{
    public function testfindByDrinkId_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($drinkComments = $this->app['drink_comments']->findByDrinkId(1))
            ->then
                ->boolean(is_array($drinkComments))->isTrue()
                ->integer(count($drinkComments))->isEqualTo(2)
        ;

        foreach ($drinkComments as $drinkComment) {
            $this->assert
                ->boolean(is_array($drinkComment))->isTrue()
                ->boolean(array_key_exists('user_email', $drinkComment))->isTrue()
            ;
        }
    }

    public function testFindOne_withExistingEntry_returnArray()
    {
        $this->assert
            ->if($comment = $this->app['drink_comments']->findOne(1, 2))
            ->then
                ->boolean(is_array($comment))->isTrue()
        ;
    }

    public function testFindOne_withInexistingEntry_returnFalse()
    {
        $this->assert
            ->if($comment = $this->app['drink_comments']->findOne(231, 2341))
            ->then
                ->boolean($comment)->isFalse()
        ;
    }
}
