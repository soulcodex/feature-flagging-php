<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

final readonly class FlagName
{
    public function __construct(private string $name)
    {
    }

    public function value(): string
    {
        return $this->name;
    }
}