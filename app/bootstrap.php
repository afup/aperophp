<?php

$autoload = require_once __DIR__.'/../vendor/.composer/autoload.php';

use Silex\Application;

$app = new Application();

// Autoloading
$app['autoloader']->registerNamespaces(
	array('Aperophp' => __DIR__ . '/../src/')
);

use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;

// Configuration
$app['config.vendor.path'] = __DIR__ . '/../vendor';
$app['config.view.path']   = __DIR__ . '/views/';
$app['config.db.path']     = __DIR__ . '/../data';

$app->register(new SymfonyBridgesServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new SessionServiceProvider());
$app->register(new DoctrineServiceProvider(), array(
			'db.options' => array(
				'driver'  => 'pdo_sqlite',
				'path'    => '/tmp/quote.db',
		),
	'db.common.class_path'  => $app['config.vendor.path'] . '/doctrine/common/lib',
	'db.dbal.class_path'    => $app['config.vendor.path'] . '/doctrine/dbal/lib',
));

$app->register(new Aperophp\Provider\Service\Model());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Resources/views',
));

if (file_exists(__DIR__.'/config.php')) {
    require_once __DIR__.'/config.php';
} else {
    require_once __DIR__.'/config.php.dist';
}

return $app;