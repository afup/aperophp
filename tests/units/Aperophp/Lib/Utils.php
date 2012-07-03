<?php

namespace tests\units\Aperophp\Lib;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Utils extends Test
{
    public function hashDataProvider()
    {
        return array(
            array('foobar', null, '6fb4c22ae98bc23cbd012c6874a2548a6c305d14'),
            array('foobar', 'another_salt', 'e32573359dfda7e36546803901ce5b714337f887'),
        );
    }

    /**
     * @dataProvider hashDataProvider
     */
    public function testHash($string, $salt, $hash)
    {
        $this->assert
            ->if($utils = new \Aperophp\Lib\Utils($this->app))
            ->then
                ->string($utils->hash($string, $salt))->isEqualTo($hash)
        ;
    }
}
