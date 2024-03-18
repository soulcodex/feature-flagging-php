<?php
declare(strict_types=1);

namespace Soulcodex\App\Shared\Domain\Flag;

use DomainException;
use Throwable;

final class FlagNotFound extends DomainException
{
    private const MESSAGE = 'Feature flag not found';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private readonly array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withFlagName(FlagName $name): self
    {
        return new self(self::MESSAGE, 0, null, ['flag_name' => $name->value()]);
    }

    public function context(): array
    {
        return $this->context;
    }
}