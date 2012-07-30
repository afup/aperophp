<?php

$app['debug']  = true;
$app['locale'] = 'fr';

$app['db.options'] = array(
    'driver' => 'pdo_mysql',
    'dbname'   => 'aperophp_test',
    'user'     => 'root',
    'password' => '',
);

$app['swiftmailer.transport'] = new \Swift_Transport_NullTransport($app['swiftmailer.transport.eventdispatcher']);
