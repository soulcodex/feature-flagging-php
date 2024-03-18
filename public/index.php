<?php

use DI\Bridge\Slim\Bridge as App;
use Soulcodex\App\DependencyInjection\DependencyInjectionCommon;

require __DIR__ . '/../vendor/autoload.php';

$commonDi = new DependencyInjectionCommon();
$app = App::create($commonDi->init());
$commonDi->run($app);