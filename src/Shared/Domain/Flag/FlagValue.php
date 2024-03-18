<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

final readonly class FlagValue
{
    public function __construct(private mixed $value)
    {
    }

    public function value(): mixed
    {
        return $this->value;
    }
}