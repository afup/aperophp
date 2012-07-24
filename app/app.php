<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/participation', new Aperophp\Provider\Controller\Participate());
$app->mount('/comment', new Aperophp\Provider\Controller\Comment());
$app->mount('/member', new Aperophp\Provider\Controller\Member());
$app->mount('/error', new Aperophp\Provider\Controller\Error());
$app->mount('/', new Aperophp\Provider\Controller\Drink());

return $app;
