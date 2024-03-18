<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Override;

use Closure;
use function Lambdish\Phunctional\each;

final readonly class DependencyOverrides
{
    /**
     * @var DependencyOverride[]
     */
    public array $overrides;

    public function __construct(DependencyOverride ...$overrides)
    {
        $this->overrides = $overrides;
    }

    public static function create(DependencyOverride ...$overrides): self
    {
        return new self(...$overrides);
    }

    public function each(Closure $callback): void
    {
        each($callback, $this->overrides);
    }
}