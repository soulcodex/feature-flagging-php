<?php
declare(strict_types=1);

namespace Soulcodex\Test\User\Integration;

use PHPUnit\Framework\Attributes\Test;
use Soulcodex\App\DependencyInjection\User\UserDependencyInjection;
use Soulcodex\Test\IntegrationTestCase;

final class CreateUserIntegrationTest extends IntegrationTestCase
{
    #[Test]
    public function itCreateAnUserUsingTheInMemoryRepository(): void
    {
        $app = $this->getAppInstance();
        UserDependencyInjection::registerRoutes()($app);

        $request = $this->createRequest(method: "POST", path: "/v1/users");
        $response = $app->handle($request);

        $this->assertEquals(201, $response->getStatusCode());
    }
}