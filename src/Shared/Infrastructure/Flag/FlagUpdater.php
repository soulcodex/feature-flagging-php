<?php

declare(strict_types=1);

namespace Soulcodex\App\Shared\Infrastructure\Flag;

use Soulcodex\App\Shared\Domain\Flag\Flag;
use Soulcodex\App\Shared\Domain\Flag\FlagName;
use Soulcodex\App\Shared\Domain\Flag\FlagNotFound;
use Soulcodex\App\Shared\Domain\Flag\FlagUpdater as Updater;
use Soulcodex\App\Shared\Domain\Flag\FlagValue;

final readonly class FlagUpdater implements Updater
{
    public function __construct(private FlagRepository $repository, private array $flags = [])
    {
    }

    public function update(FlagName $name, FlagValue $value): void
    {
        $this->guard($name);

        $defaultFlagValue = $this->defaultValue($name);

        $this->repository->save(new Flag($name, $defaultFlagValue, $value));
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