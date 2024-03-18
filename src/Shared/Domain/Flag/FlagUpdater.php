<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

interface FlagUpdater
{
    public function update(FlagName $name, FlagValue $value): void;
}