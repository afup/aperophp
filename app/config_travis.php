<?php

$app['db.options'] = array_merge(
    $app['db.options'], 
    array(
        'user'      => 'root',
        'password'  => '',
    )
);
