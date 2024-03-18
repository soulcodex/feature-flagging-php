<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Infrastructure\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Soulcodex\App\Shared\Domain\Bus\Command\CommandBus;
use Soulcodex\App\Shared\Domain\Ulid\UlidProvider;
use Soulcodex\App\User\Application\Create\CreateUserCommand;

final readonly class CreateUserController
{
    public function __construct(
        private UlidProvider $ulidProvider,
        private CommandBus   $bus,
    )
    {
    }

    public function __invoke(Request $request, Response $response): Response
    {
        $userId = $this->ulidProvider->new();
        $userEmail = sprintf("%s@proton.io", strtolower($userId->value()));

        $this->bus->dispatch(new CreateUserCommand($userId->value(), $userEmail));

        return $response->withStatus(201);
    }
}