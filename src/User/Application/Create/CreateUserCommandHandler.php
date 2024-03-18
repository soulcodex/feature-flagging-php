<?php

declare(strict_types=1);

namespace Soulcodex\App\User\Application\Create;

use Soulcodex\App\User\Domain\UserCreationStrategy;

final readonly class CreateUserCommandHandler
{
    public function __construct(private UserCreationStrategy $creationStrategy)
    {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $this->creationStrategy->create($command->id(), $command->email());
    }
}