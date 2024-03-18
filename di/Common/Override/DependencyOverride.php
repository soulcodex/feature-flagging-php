<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Override;

final readonly class DependencyOverride
{
    public function __construct(public string $overrideName, public mixed $definition)
    {
    }

    public static function create(string $overrideName, mixed $definition): self
    {
        return new self($overrideName, $definition);
    }
}