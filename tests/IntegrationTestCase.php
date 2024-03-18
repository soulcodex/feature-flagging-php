<?php
declare(strict_types=1);

namespace Soulcodex\Test;

use DI\Bridge\Slim\Bridge as App;
use PHPUnit\Framework\TestCase;
use Slim\App as SlimApp;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;
use Soulcodex\App\DependencyInjection\Common\Override\DependencyOverride;
use Soulcodex\App\DependencyInjection\Common\Override\DependencyOverrides;
use Soulcodex\App\DependencyInjection\DependencyInjectionCommon;
use Soulcodex\App\Shared\Domain\Ulid\UlidProvider;
use Soulcodex\App\Shared\Infrastructure\Ulid\FixedUlidProvider;

class IntegrationTestCase extends TestCase
{
    protected function getAppInstance(): SlimApp
    {
        $commonDi = new DependencyInjectionCommon();
        $overrides = DependencyOverrides::create($this->overrideUlidProvider());
        return App::create($commonDi->initWithOverrides($overrides));
    }

    protected function createRequest(
        string $method,
        string $path,
        array  $headers = ['HTTP_ACCEPT' => 'application/json'],
        array  $cookies = [],
        array  $serverParams = []
    ): Request
    {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new Request($method, $uri, $h, $cookies, $serverParams, $stream);
    }

    private function overrideUlidProvider(): DependencyOverride
    {
        return new DependencyOverride(UlidProvider::class, function () {
            return new FixedUlidProvider();
        });
    }
}