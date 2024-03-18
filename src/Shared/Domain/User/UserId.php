<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\User;

final readonly class UserId
{
    public function __construct(public string $id)
    {
    }

    public static function create(string $id): self
    {
        return new self($id);
    }
}