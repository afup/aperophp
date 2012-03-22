<?php

require_once __DIR__.'/autoload.php';

use Silex\Application;

$app = new Application();


// *******
// ** Configuration loading
// *******
// Load default configuration
require_once __DIR__.'/config.php.dist';
// Local installation configuration overloading
if (file_exists(__DIR__.'/config.php')) {
    require_once __DIR__.'/config.php';
}
// *******

// Autoloading
$app['autoloader']->registerNamespaces(array(
    'Symfony'  => __DIR__.'/../vendor',
    'Aperophp' => __DIR__.'/../src/'
));

$app['autoloader']->registerPrefixes(array(
    'Swift_' => __DIR__.'/../vendor/SwiftMailer/lib/classes/',
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
use Silex\Provider\SwiftmailerServiceProvider;

$app->register(new SymfonyBridgesServiceProvider());
$app->register(new UrlGeneratorServiceProvider());
$app->register(new FormServiceProvider());
$app->register(new ValidatorServiceProvider());
$app->register(new Aperophp\Provider\Service\Model());

$app->register(new SessionServiceProvider(), array(
    'locale' => $app['locale'],
    'session.storage.options' => array(
        'auto_start' => true),
));

$app->register(new DoctrineServiceProvider(), array(
    'db.dbal.class_path'    => __DIR__.'/../vendor/doctrine-dbal/lib',
    'db.common.class_path'  => __DIR__.'/../vendor/doctrine-common/lib',
));

// *******
// ** Twig
// *******
$app->register(new TwigServiceProvider(), array(
    'twig.path'         => __DIR__.'/../src/Resources/views',
    'twig.class_path'   => __DIR__.'/../vendor/twig/lib',
    'twig.options'      => array('debug' => $app['debug']),
));

// Add Twig extensions
$app['twig.configure'] = $app->protect(function($twig) {
    $twig->addExtension(new Twig_Extensions_Extension_Debug());
});
// *******


// *******
// ** Translations
// *******
$app->register(new TranslationServiceProvider(array(
    'locale_fallback'           => 'fr',
    'locale'                    => $app['locale'],
    'translation.class_path'    => __DIR__.'/../Symfony/Component/Translation',
)));

$app['translator.messages'] = array(
    'fr' => array(
        'January'   => 'Janvier',
        'February'  => 'Février',
        'March'     => 'Mars',
        'April'     => 'Avril',
        'May'       => 'Mai',
        'June'      => 'Juin',
        'July'      => 'Juillet',
        'August'    => 'Aout',
        'September' => 'Septembre',
        'October'   => 'Octobre',
        'November'  => 'Novembre',
        'December'  => 'Décembre',
    ),
);
// *******

$app->register(new SwiftmailerServiceProvider(array(
    'swiftmailer.options'       => $app['mail.options'],
    'swiftmailer.class_path'    => __DIR__.'/../vendor/SwiftMailer/lib/classes/', 
)));
//$app['swiftmailer.transport'] = new \Swift_Transport_SpoolTransport($app['swiftmailer.transport.eventdispatcher']);

return $app;