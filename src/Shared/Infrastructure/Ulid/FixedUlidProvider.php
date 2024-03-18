<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Ulid;

use Soulcodex\App\Shared\Domain\Ulid\Ulid;
use Soulcodex\App\Shared\Domain\Ulid\UlidProvider;
use Ulid\Ulid as BaseUlid;

final class FixedUlidProvider implements UlidProvider
{
    private static Ulid|null $ulid = null;

    public function new(): Ulid
    {
        return self::$ulid = self::$ulid ?: new Ulid((new BaseUlid)->generate());
    }
}