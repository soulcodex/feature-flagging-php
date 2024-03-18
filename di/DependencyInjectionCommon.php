<?php

declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection;

use DI\Container;
use Flagsmith\Flagsmith;
use Flagsmith\Models\DefaultFlag;
use Flagsmith\Utils\Retry;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Predis\Client;
use Predis\ClientInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Slim\App;
use Soulcodex\App\DependencyInjection\Common\Override\DependencyOverride;
use Soulcodex\App\DependencyInjection\Common\Override\DependencyOverrides;
use Soulcodex\App\DependencyInjection\User\UserDI;
use Soulcodex\App\Shared\Domain\Flag\FlagFetcher;
use Soulcodex\App\Shared\Infrastructure\Controller\Flag\SearchFlagByNameController;
use Soulcodex\App\Shared\Infrastructure\Controller\IndexController;
use Soulcodex\App\Shared\Infrastructure\Flag\FlagRepository;
use Soulcodex\App\Shared\Infrastructure\Flag\FlagRetriever;
use Soulcodex\App\Shared\Infrastructure\Flag\FlagsmithFlagRepository;
use Soulcodex\App\Shared\Infrastructure\Flag\RedisFlagRepository;
use Soulcodex\App\Shared\Infrastructure\Flag\UnleashFlagRepository;
use Soulcodex\App\Shared\Infrastructure\Ulid\RandomUlidProvider;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Unleash\Client\Unleash;
use Unleash\Client\UnleashBuilder;

final class DependencyInjectionCommon
{
    private array $modules = [
        UserDI::class
    ];

    public final function init(): ContainerInterface
    {
        $container = new Container();

        $this->initCommon($container);

        foreach ($this->modules as $module) {
            $module::init($container);
        }

        return $container;
    }

    public final function initWithOverrides(DependencyOverrides $overrides): ContainerInterface
    {
        $container = new Container();

        $this->initCommon($container);

        foreach ($this->modules as $module) {
            $module::init($container);
        }

        $overrides->each(
            fn(DependencyOverride $override) => $container->set(
                $override->overrideName,
                $override->definition
            )
        );

        return $container;
    }

    public function run(App $app): void
    {
        $this->initCommonRoutes($app);

        $app->run();
    }

    private function initCommonRoutes(App $app): void
    {
        $app->get('/', [IndexController::class, '__invoke']);

        $app->get('/feature-flags/{flagName}', [SearchFlagByNameController::class, '__invoke']);
    }

    private function initCommon(Container $container): void
    {
        $this->initRedisClient($container);
        $this->initLogger($container);
        $container->set(RandomUlidProvider::class, fn() => new RandomUlidProvider());

        $this->initRedisFeatureFlagging($container);
        // $this->initUnleashFeatureFlagging($container);
        // $this->initFlagsmithFeatureFlagging($container);
    }

    private function initRedisClient(Container $container): void
    {
        $container->set(ClientInterface::class, function () {
            return new Client('redis://redis:6379');
        });
    }

    private function initUnleashFeatureFlagging(Container $container): void
    {
        $container->set(Unleash::class, function () use ($container) {
            return UnleashBuilder::create()
                ->withAppName('Feature Flagging Talk')
                ->withAppUrl("https://app.unleash-hosted.com/demo/api")
                ->withInstanceId("feature-flagging-v1-tech-talk")
                ->withHeaders(['Authorization' => "feature-flagging-v1-tech-talk:development.335156dc13ce3346b99f67c7ef6afc50c21e6614b4e8996c0d9b18da"])
                ->withCacheTimeToLive(5)
                ->withCacheHandler(new Psr16Cache(new RedisAdapter($container->get(ClientInterface::class))))
                ->withBootstrap(['new_user_persistence_activated' => false])
                ->build();
        });

        $container->set(FlagRepository::class, function () use ($container) {
            $unleashClient = $container->get(Unleash::class);
            return new UnleashFlagRepository($unleashClient);
        });

        $container->set(FlagFetcher::class, function () use ($container) {
            return new FlagRetriever(
                $container->get(FlagRepository::class),
                ['new_user_persistence_activated' => false]
            );
        });
    }

    private function initFlagsmithFeatureFlagging(Container $container): void
    {
        $container->set(Flagsmith::class, function () use ($container) {
            $flagsmith = (new Flagsmith('ser.P8yRUYsvFFBfLc6EL5Hzfd'))
                ->withDefaultFlagHandler(function (string $flagName) {
                    return (new DefaultFlag())
                        ->withEnabled(false)
                        ->withValue(null);
                })
                ->withTimeToLive(5)
                ->withRetries(new Retry(3))
                ->withCache(new Psr16Cache(new RedisAdapter($container->get(ClientInterface::class))));

            $flagsmith->updateEnvironment();

            return $flagsmith;
        });

        $container->set(FlagRepository::class, function () use ($container) {
            $flagsmithClient = $container->get(Flagsmith::class);
            return new FlagsmithFlagRepository($flagsmithClient);
        });

        $container->set(FlagFetcher::class, function () use ($container) {
            return new FlagRetriever(
                $container->get(FlagRepository::class),
                ['new_user_persistence_activated' => false]
            );
        });
    }

    private function initRedisFeatureFlagging(Container $container): void
    {
        $container->set(FlagRepository::class, function () use ($container) {
            $redisClient = $container->get(ClientInterface::class);
            return new RedisFlagRepository($redisClient);
        });

        $container->set(FlagFetcher::class, function () use ($container) {
            return new FlagRetriever(
                $container->get(FlagRepository::class),
                ['new_user_persistence_activated' => false]
            );
        });
    }

    private function initLogger(Container $container): void
    {
        $container->set(LoggerInterface::class, function () {
            $logger = new Logger("feature-flagging-v1");

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler("php://stdout", Level::Warning);
            $logger->pushHandler($handler);

            return $logger;
        });
    }
}