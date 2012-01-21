<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->get('/', function() use ($app) {
  $app['model']('Aperos');
  return $app['twig']->render('index.html.twig');
});

return $app;