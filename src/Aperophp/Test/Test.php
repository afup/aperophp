<?php

namespace Aperophp\Test;

use mageekguy\atoum;

class Test extends atoum\test
{
    public function beforeTestMethod($method)
    {
        $this->app = require __DIR__.'/../../../app/app.php';
        require __DIR__.'/../../../app/config_test.php';
    }
}
