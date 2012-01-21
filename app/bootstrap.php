<?php

$autoload = require_once __DIR__.'/../vendor/.composer/autoload.php';

use Silex\Application;

$app = new Application();

use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

$app->register(new SymfonyBridgesServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new DoctrineServiceProvider());

$app->register(new PrestaQuotes\Provider\Service\Model());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Resources/views',
));

if (file_exists(__DIR__.'/config.php')) {
    require_once __DIR__.'/config.php';
} else {
    require_once __DIR__.'/config.php.dist';
}

return $app;