<?php

namespace Aperophp\Test;

use mageekguy\atoum;
use Symfony\Component\HttpKernel\Client;

class Test extends atoum\test
{
    public function beforeTestMethod($method)
    {
        $this->app = require __DIR__.'/../../../app/app.php';
        require __DIR__.'/../../../app/config_test.php';

        // Isolate DB
        $this->app['db']->beginTransaction();
    }

    /**
     * Creates a Client.
     *
     * @param array $server An array of server parameters
     *
     * @return Client A Client instance
     */
    public function createClient(array $server = array())
    {
        return new Client($this->app, $server);
    }

    public function afterTestMethod($method)
    {
        $this->app['db']->rollback();
    }
}
