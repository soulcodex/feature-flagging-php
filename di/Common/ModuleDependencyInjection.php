<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common;

use DI\Container;

abstract readonly class ModuleDependencyInjection
{
    protected function __construct(protected Container $container)
    {
    }

    public static function bootstrap(Container $container): static
    {
        return new static($container);
    }

    abstract public function init(): void;
}