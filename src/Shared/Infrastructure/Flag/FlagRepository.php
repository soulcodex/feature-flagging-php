<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagNotFound;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;

interface FlagRepository
{
    public function save(Flag $flag): void;

    /**
     * @throws FlagNotFound
     */
    public function searchByName(FlagName $name): ?FlagValue;
}