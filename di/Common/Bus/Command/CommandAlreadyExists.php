<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Bus\Command;

use RuntimeException;
use Throwable;

final class CommandAlreadyExists extends RuntimeException
{
    private const MESSAGE = 'Command already exists';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private readonly array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public static function withCommandClass(string $commandClass): self
    {
        return new self(self::MESSAGE, 0, null, ['command_class' => $commandClass]);
    }

    public function context(): array
    {
        return $this->context;
    }
}