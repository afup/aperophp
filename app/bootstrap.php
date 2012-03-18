<?php

require_once __DIR__.'/autoload.php';

use Silex\Application;

$app = new Application();

// Autoloading
$app['autoloader']->registerNamespaces(array(
    'Symfony'  => __DIR__.'/../vendor',
    'Aperophp' => __DIR__.'/../src/'
));

// Utils
$app['utils'] = $app->share(function() use ($app) {
    return new \Aperophp\Lib\Utils($app);
});

use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TranslationServiceProvider;

$app->register(new SymfonyBridgesServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new SessionServiceProvider(), array(
    'locale' => 'fr',
    'session.storage.options' => array(
        'auto_start' => true),
));
$app->register(new DoctrineServiceProvider(), array(
    'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__.'/../vendor/doctrine-common/lib',
));

$app->register(new Aperophp\Provider\Service\Model());

$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/Resources/views',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
    'twig.options' => array('debug' => true),
));

$app->register(new TranslationServiceProvider(array(
    'locale_fallback'           => 'fr',
    'locale'                    => 'fr',
    'translation.class_path'    => __DIR__.'/../Symfony/Component/Translation',
)));

$oldTwigConfiguration = isset($app['twig.configure']) ? $app['twig.configure']: function(){};
$app['twig.configure'] = $app->protect(function($twig) use ($oldTwigConfiguration) {
    $oldTwigConfiguration($twig);
    $twig->addExtension(new Twig_Extensions_Extension_Debug());
});

$app['translator.messages'] = array(
    'fr' => array(
        'January' => 'Janvier',
        'February' => 'Février',
        'March' => 'Mars',
        'April' => 'Avril',
        'May' => 'Mai',
        'June' => 'Juin',
        'July' => 'Juillet',
        'August' => 'Aout',
        'September' => 'Septembre',
        'October' => 'Octobre',
        'November' => 'Novembre',
        'December' => 'Décembre',
    ),
);

if (file_exists(__DIR__.'/config.php')) {
    require_once __DIR__.'/config.php';
} else {
    require_once __DIR__.'/config.php.dist';
}

return $app;