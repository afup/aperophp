<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/drink', new Aperophp\Provider\Controller\Drink());
$app->mount('/drink/participation', new Aperophp\Provider\Controller\Participate());
$app->mount('/drink/comment', new Aperophp\Provider\Controller\Comment());
$app->mount('/member', new Aperophp\Provider\Controller\Member());
$app->mount('/error', new Aperophp\Provider\Controller\Error());

return $app;
