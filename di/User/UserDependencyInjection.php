<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\User;

use Closure;
use Predis\ClientInterface;
use Slim\App;
use Soulcodex\App\DependencyInjection\Common\Bus\Command\InteractWithCommandBus;
use Soulcodex\App\DependencyInjection\Common\ModuleDependencyInjection;
use Soulcodex\App\Shared\Domain\Flag\FlagRetriever;
use Soulcodex\App\User\Application\Create\CreateUserCommand;
use Soulcodex\App\User\Application\Create\CreateUserCommandHandler;
use Soulcodex\App\User\Domain\UserCreationStrategy;
use Soulcodex\App\User\Infrastructure\Controller\CreateUserController;
use Soulcodex\App\User\Infrastructure\Persistence\InMemoryUserRepository;
use Soulcodex\App\User\Infrastructure\Persistence\RedisUserRepository;

final readonly class UserDependencyInjection extends ModuleDependencyInjection
{
    use InteractWithCommandBus;

    public function init(): void
    {
        $this->container->set(RedisUserRepository::class, function () {
            return new RedisUserRepository($this->container->get(ClientInterface::class));
        });

        $this->container->set(InMemoryUserRepository::class, fn() => new InMemoryUserRepository());

        $this->container->set(UserCreationStrategy::class, function () {
            return new UserCreationStrategy(
                $this->container->get(InMemoryUserRepository::class),
                $this->container->get(RedisUserRepository::class),
                $this->container->get(FlagRetriever::class),
            );
        });

        $this->container->set(CreateUserCommandHandler::class, function () {
            return new CreateUserCommandHandler($this->container->get(UserCreationStrategy::class));
        });

        $this->registerCommands([
            CreateUserCommand::class => CreateUserCommandHandler::class
        ]);
    }

    public static function registerRoutes(): Closure
    {
        return function (App $app): void {
            $app->post('/v1/users', [CreateUserController::class, '__invoke']);
        };
    }
}