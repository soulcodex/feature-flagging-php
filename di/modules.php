<?php

use Soulcodex\App\DependencyInjection\Common\ModuleDependencyInjection;
use Soulcodex\App\DependencyInjection\User\UserDependencyInjection;

/** @var ModuleDependencyInjection[] $modules */
$modules = [
    UserDependencyInjection::class
];

return $modules;