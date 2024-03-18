<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Flagsmith\Exceptions\FlagsmithThrowable;
use Flagsmith\Flagsmith as FlagsmithClient;
use Flagsmith\Models\BaseFlag;
use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;

final readonly class FlagsmithFlagRepository implements FlagRepository
{
    public function __construct(private FlagsmithClient $client)
    {
    }

    public function save(Flag $flag): void
    {
    }

    public function searchByName(FlagName $name): ?FlagValue
    {
        try {
            $flags = $this->client->getEnvironmentFlags();
            $value = $flags->getFlag($name->value());

            if (!empty($value->getValue())) {
                return new FlagValue($this->decodeValueOrRaw($value));
            }

            return new FlagValue($value->getEnabled());
        } catch (FlagsmithThrowable) {
            return null;
        }
    }

    private function decodeValueOrRaw(BaseFlag $flag): mixed
    {
        $value = json_decode($flag->getValue());

        if (json_last_error() === JSON_ERROR_NONE) {
            return $value;
        }

        return $flag->getValue();
    }
}