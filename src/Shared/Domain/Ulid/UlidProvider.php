<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Ulid;

interface UlidProvider
{
    public function new(): Ulid;
}