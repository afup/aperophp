<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/apero', new Aperophp\Provider\Controller\Aperos());
$app->mount('/member', new Aperophp\Provider\Controller\Member());

return $app;