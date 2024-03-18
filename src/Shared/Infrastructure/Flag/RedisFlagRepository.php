<?php

declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Predis\ClientInterface as RedisClient;
use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;

final readonly class RedisFlagRepository implements FlagRepository
{
    private const PREFIX = 'feature_flag';

    public function __construct(private RedisClient $client)
    {
    }

    public function save(Flag $flag): void
    {
        $this->client->set($this->key($flag->name()), serialize($flag->value()->value()));
    }

    public function searchByName(FlagName $name): ?FlagValue
    {
        $flagValue = null;
        $value = $this->client->get($this->key($name));

        if (false !== $value && !is_null($value)) {
            $flagValue = new FlagValue(unserialize($value));
        }

        return $flagValue;
    }

    private function key(FlagName $name): string
    {
        return sprintf('%s::%s', self::PREFIX, $name->value());
    }
}