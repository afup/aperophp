<?php

$app = require_once __DIR__.'/bootstrap.php';

$app->get('/', function() use ($app) {
  $app['model']('Apero');
  return $app['twig']->render('index.html.twig');
});

return $app;