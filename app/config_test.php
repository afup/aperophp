<?php

$app['debug']  = true;
$app['locale'] = 'fr';
$app['secret'] = 'enough_secret_for_tests';

$app['db.options'] = array(
    // Experiencing problems with sqlite...
    //'driver' => 'pdo_sqlite',
    //'memory' => true,
    'driver'   => 'pdo_mysql',
    'dbname'   => 'aperophp_test',
    'user'     => $app['db.options']['user'],
    'password' => $app['db.options']['password'],
);

$app['swiftmailer.transport'] = new \Swift_Transport_NullTransport($app['swiftmailer.transport.eventdispatcher']);
