<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagFetcher;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagNotFound;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;

final readonly class FlagRetriever implements FlagFetcher
{
    public function __construct(private FlagRepository $repository, private array $flags = [])
    {
    }

    public function flagByName(FlagName $name): Flag
    {
        $this->guard($name);

        $defaultFlagValue = $this->defaultValue($name);
        $flagValue = $this->repository->searchByName($name);

        return new Flag($name, $defaultFlagValue, $flagValue);
    }

    private function guard(FlagName $name): void
    {
        if (!array_key_exists($name->value(), $this->flags)) {
            throw FlagNotFound::withFlagName($name);
        }
    }

    private function defaultValue(FlagName $name): FlagValue
    {
        $flagValue = $this->flags[$name->value()];

        return new FlagValue($flagValue);
    }
}