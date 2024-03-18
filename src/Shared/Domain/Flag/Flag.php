<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

final readonly class Flag
{
    public function __construct(private FlagName $name, private FlagValue $default, private ?FlagValue $value)
    {
    }

    public function name(): FlagName
    {
        return $this->name;
    }

    public function default(): FlagValue
    {
        return $this->default;
    }

    public function value(): ?FlagValue
    {
        return $this->value;
    }
}