<?php

namespace tests\units\Aperophp\Lib;

require_once __DIR__.'/../../../../vendor/autoload.php';

use Aperophp\Test\Test;

class Utils extends Test
{
    public function hashDataProvider()
    {
        return array(
            array('foobar', null, '633a727de23b0260ff30d24dfd65199dd4953d55'),
            array('foobar', 'another_salt', 'e32573359dfda7e36546803901ce5b714337f887'),
            array('password', null, '1d85bd100e0dd11b20f67a5834c8c2d67e7d9720'),
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
