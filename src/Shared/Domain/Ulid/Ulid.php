<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Ulid;

final readonly class Ulid
{
    public function __construct(protected string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}