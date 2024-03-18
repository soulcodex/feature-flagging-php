<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;
use Unleash\Client\DTO\VariantPayload;
use Unleash\Client\Unleash;

final readonly class UnleashFlagRepository implements FlagRepository
{
    public function __construct(private Unleash $client)
    {
    }

    public function save(Flag $flag): void
    {
    }

    public function searchByName(FlagName $name): ?FlagValue
    {
        $variant = $this->client->getVariant(featureName: $name->value());

        if ($variant->isEnabled() && ($payload = $variant->getPayload()) instanceof VariantPayload) {
            return new FlagValue($payload->jsonSerialize());
        }

        return new FlagValue($this->client->isEnabled($name->value()));
    }
}