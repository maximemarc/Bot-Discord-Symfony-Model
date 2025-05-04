<?php

namespace App\Discord\Listener;

use App\Discord\Abstract\AbstractDiscordListener;
use App\Discord\Manager\CommandManager;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;

/***
 * Service pour exécuter les différentes commandes que le bot peut réaliser.
 * Les commandes sont gérées dans le service, à l'aide du DIC afin d'éviter de déclarer chaque commande
 */
class CommandListener extends AbstractDiscordListener
{
    public function __construct(private readonly CommandManager $commandManager)
    {
    }

    public function getDiscordEvent(): string
    {
        return Event::MESSAGE_CREATE;
    }

    public function register(Discord $discord): void
    {
        $discord->on(Event::MESSAGE_CREATE, [$this, 'handle']);
    }

    public function handle(Message $message): void
    {
        $this->commandManager->handleCommand($message);
    }
}
