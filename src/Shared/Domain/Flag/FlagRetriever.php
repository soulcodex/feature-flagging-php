<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

interface FlagRetriever
{
    public function flagByName(FlagName $name): Flag;
}