<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Application\Create;

use Soulcodex\App\Shared\Domain\Bus\Command\Command;

final readonly class CreateUserCommand implements Command
{
    public function __construct(private string $id, private string $email)
    {
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string

    {
        return $this->email;
    }
}