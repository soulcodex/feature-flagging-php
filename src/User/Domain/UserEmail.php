<?php
declare(strict_types=1);

namespace Soulcodex\App\User\Domain;

final readonly class UserEmail
{
    public function __construct(public string $email)
    {
    }

    public static function create(string $email): self
    {
        return new self($email);
    }
}