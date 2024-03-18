<?php

use DI\Bridge\Slim\Bridge as App;
use Soulcodex\App\DependencyInjection\DependencyInjection;

require __DIR__ . '/../vendor/autoload.php';

$commonDi = new DependencyInjection();
$app = App::create($commonDi->init());
$commonDi->run($app);