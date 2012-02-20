<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/apero', new Aperophp\Provider\Controller\Aperos());
$app->mount('/apero/participation', new Aperophp\Provider\Controller\DrinkParticipation());
$app->mount('/member', new Aperophp\Provider\Controller\Member());

return $app;
