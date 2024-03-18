<?php
declare(strict_types=1);

namespace Soulcodex\App\DependencyInjection\Common\Bus\Command;

trait InteractWithCommandBus
{
    public function registerCommands(array $commands = []): void
    {
        $commandBusRegisterer = $this->commandBusRegisterer();

        foreach ($commands as $commandClass => $handlerClass) {
            $commandBusRegisterer->register($commandClass, $handlerClass);
        }
    }

    private function commandBusRegisterer(): CommandBusRegisterer
    {
        if ($this->container->has(CommandBusRegisterer::class)) {
            return $this->container->get(CommandBusRegisterer::class);
        }

        $this->container->set(CommandBusRegisterer::class, function () {
            return new CommandBusRegisterer();
        });

        return $this->container->get(CommandBusRegisterer::class);
    }
}