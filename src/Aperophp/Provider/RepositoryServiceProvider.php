<?php

namespace Aperophp\Provider;

use Silex\ServiceProviderInterface;
use Silex\Application;

class RepositoryServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        if (!isset($app['repository.repositories'])) {
            return;
        }

        foreach ($app['repository.repositories'] as $label => $class) {
            $app[$label] = $app->share(function($app) use ($class) {
                return new $class($app['db']);
            });
        }
    }

    public function boot(Application $app)
    {
    }
}
