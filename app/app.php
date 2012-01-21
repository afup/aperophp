<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->mount('/', new Aperophp\Provider\Controller\Aperos());

return $app;