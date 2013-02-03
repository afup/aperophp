<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Provider\SymfonyBridgesServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\FormServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\TranslationServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;

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

// Utils
$app['utils'] = $app->share(function() use ($app) {
    return new \Aperophp\Lib\Utils($app);
});

$app['mail_factory'] = $app->share(function() use ($app) {
    return new \Aperophp\Lib\MailFactory($app);
});

$app->register(new UrlGeneratorServiceProvider());

$app->register(new FormServiceProvider());
$app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions) use ($app) {
    $extensions[] = new Aperophp\Form\FormExtension($app);

    return $extensions;
}));

$app->register(new ValidatorServiceProvider());

$app->register(new SessionServiceProvider(), array(
    'locale' => $app['locale'],
    'session.storage.options' => array(
        'auto_start' => true
    ),
));

$app->register(new DoctrineServiceProvider());

$app['repository.repositories'] = array(
    'cities'               => 'Aperophp\Repository\City',
    'drinks'               => 'Aperophp\Repository\Drink',
    'drink_comments'       => 'Aperophp\Repository\DrinkComment',
    'drink_participants'   => 'Aperophp\Repository\DrinkParticipant',
    'members'              => 'Aperophp\Repository\Member',
    'users'                => 'Aperophp\Repository\User',
);
$app->register(new Aperophp\Provider\RepositoryServiceProvider());

// *******
// ** Twig
// *******
$app->register(new TwigServiceProvider(), array(
    'twig.options' => array(
        'debug' => $app['debug']
    ),
    'twig.path' => array(__DIR__ . '/../src/Resources/views')
));

// Add Twig extensions
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Debug());

    return $twig;
}));
// *******


// *******
// ** Translations
// *******
$app->register(new TranslationServiceProvider(array(
    'locale_fallback' => 'fr',
    'locale'          => $app['locale'],
)));

$app['translator.domains'] = array(
    'messages' => array(
        'fr' => array(
            'January'   => 'Janvier',
            'February'  => 'Février',
            'March'     => 'Mars',
            'April'     => 'Avril',
            'May'       => 'Mai',
            'June'      => 'Juin',
            'July'      => 'Juillet',
            'August'    => 'Août',
            'September' => 'Septembre',
            'October'   => 'Octobre',
            'November'  => 'Novembre',
            'December'  => 'Décembre',
            'Monday'  => 'Lundi',
            'Tuesday'  => 'Mardi',
            'Wednesday'  => 'Mercredi',
            'Thursday'  => 'Jeudi',
            'Friday'  => 'Vendredi',
            'Saturday'  => 'Samedi',
            'Sunday'  => 'Dimanche',
            'drink'  => 'Apéro',
            'drink'  => 'Apéro',
            'talk'   => 'Mini-conf',
            'For sure, I will be there' => 'Présence assurée',
            'I will probably be there'  => 'Devrait être là',
            'I will try to be there'    => 'Essayera d\'être là',
            'I won\'t be there'         => 'Ne sera pas là',
        )
    ),
    'validators' => array(
        'fr' => array(
            'The date must be in the future'  => 'La date doit être future',
        )
    )
);
// *******

// *******
// ** Mail
// *******
$app->register(new SwiftmailerServiceProvider(array(
    'swiftmailer.options'       => $app['mail.options'],
    'swiftmailer.class_path'    => __DIR__.'/../vendor/SwiftMailer/lib/classes/',
)));
// *******

// *******
// ** Gravatar
// *******
$app->register(new SilexGravatar\GravatarExtension(), array(
    'gravatar.class_path' => __DIR__ . '/../vendor/mheap/Silex-Gravatar/src',
    'gravatar.cache_dir'  => __DIR__ . '/../cache',
    'gravatar.cache_ttl'  => 240, // 240 seconds
    'gravatar.options' => array(
        'size' => 100,
        'rating' => Gravatar\Service::RATING_G,
        'secure' => true,
        'default'   => Gravatar\Service::DEFAULT_MM,
        'force_default' => false
    )
));
// *******

return $app;
