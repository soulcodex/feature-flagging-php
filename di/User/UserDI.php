<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\User;

use DI\Container;
use Predis\ClientInterface;
use Soulcodex\App\User\Infrastructure\Persistence\RedisUserRepository;

final readonly class UserDI
{
    public static function init(Container $container): void
    {
        $container->set(RedisUserRepository::class, function () use ($container) {
            return new RedisUserRepository($container->get(ClientInterface::class));
        });
    }
}