<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Bus\Command;

use RuntimeException;
use Throwable;

final class CommandRegisteringFailure extends RuntimeException
{
    private const MESSAGE = 'Error registering a command';

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null, private readonly array $context = [])
    {
        parent::__construct($message, $code, $previous);
    }

    public static function dueCommandNotExist(string $commandClass): self
    {
        return new self(self::MESSAGE, 0, null, ['command_class' => $commandClass]);
    }

    public static function dueCommandHandlerNotExist(string $commandClass, string $handlerClass): self
    {
        return new self(
            self::MESSAGE,
            0,
            null,
            ['command_class' => $commandClass, 'command_handler_class' => $handlerClass]
        );
    }

    public function context(): array
    {
        return $this->context;
    }
}