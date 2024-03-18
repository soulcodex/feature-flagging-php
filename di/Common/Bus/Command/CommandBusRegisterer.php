<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Bus\Command;

final class CommandBusRegisterer
{
    public function __construct(private array $handlers = [])
    {
    }

    public function handlers(): array
    {
        return $this->handlers;
    }

    public function register(string $commandClass, string $handlerClass): void
    {
        $this->guard($commandClass, $handlerClass);

        $this->handlers[$commandClass] = $handlerClass;
    }

    private function guard(string $commandClass, string $handlerClass): void
    {
        if (!class_exists($commandClass)) {
            throw CommandRegisteringFailure::dueCommandNotExist($commandClass);
        }

        if (!class_exists($handlerClass)) {
            throw CommandRegisteringFailure::dueCommandHandlerNotExist($commandClass, $handlerClass);
        }

        if (array_key_exists($commandClass, $this->handlers)) {
            throw CommandAlreadyExists::withCommandClass($commandClass);
        }
    }
}