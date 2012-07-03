<?php

namespace tests\units\Aperophp\Model;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class DrinkComment extends Test
{
    public function testfindByDrinkId_withExistingEntry_returnIt()
    {
        $this->assert
            ->if($drinkComments = \Aperophp\Model\DrinkComment::findByDrinkId($this->app['db'], 1))
            ->then
                ->boolean(is_array($drinkComments))->isTrue()
                ->integer(count($drinkComments))->isEqualTo(2)
        ;

        foreach ($drinkComments as $drinkComment) {
            $this->assert
                ->boolean(is_object($drinkComment))->isTrue()
                ->boolean($drinkComment instanceof \Aperophp\Model\DrinkComment)->isTrue()
            ;
        }
    }

    public function testSaveNewDrinkComment_withValidObject_returnTrue()
    {
        $drinkComment = new \Aperophp\Model\DrinkComment($this->app['db']);
        $drinkComment->setCreatedAt('2012-07-23 22:43:32');
        $drinkComment->setContent('Un nouveau commentaire');
        $drinkComment->setDrinkId(1);
        $drinkComment->setUserId(3);

        $this->assert
            ->boolean($drinkComment->save())->isTrue()
        ;
    }
}
