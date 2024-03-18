<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Ulid;

use Soulcodex\App\Shared\Domain\Ulid\Ulid;
use Soulcodex\App\Shared\Domain\Ulid\UlidProvider;
use Ulid\Ulid as BaseUlid;

final readonly class RandomUlidProvider implements UlidProvider
{
    public function new(): Ulid
    {
        return new Ulid((new BaseUlid())->generate());
    }
}