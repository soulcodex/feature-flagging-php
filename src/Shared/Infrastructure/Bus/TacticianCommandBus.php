<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Bus;

use League\Tactician\CommandBus as Bus;
use Soulcodex\App\Shared\Domain\Bus\Command\Command;
use Soulcodex\App\Shared\Domain\Bus\Command\CommandBus;

final readonly class TacticianCommandBus implements CommandBus
{
    public function __construct(private Bus $commandBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->handle($command);
    }
}