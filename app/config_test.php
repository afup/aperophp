<?php

$app['debug']  = true;
$app['locale'] = 'fr';

$app['secret']  = 'change_me_im_not_secret';

$app['db.options'] = array_merge(
    $app['db.options'],
    array(
        'dbname'   => $app['db.options']['dbname'] . '_test',
    )
);

$app['swiftmailer.transport'] = new \Swift_Transport_NullTransport($app['swiftmailer.transport.eventdispatcher']);
