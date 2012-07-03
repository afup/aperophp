<?php

$app['debug']  = true;
$app['locale'] = 'fr';
$app['secret'] = 'enough_secret_for_tests';

$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path'   => __DIR__.'/../cache/app_test.db',
);

$app['swiftmailer.options'] = array(
    'host'       => 'localhost',
    'port'       => '25',
    'username'   => '',
    'password'   => '',
    'encryption' => null,
    'auth_mode'  => null,
);
