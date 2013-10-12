<?php

$app['db.options'] = array_merge(
    $app['bd.options'], 
    array(
        'user'      => 'root',
        'password'  => '',
    )
);
