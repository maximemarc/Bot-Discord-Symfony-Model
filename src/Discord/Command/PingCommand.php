<?php

namespace App\Discord\Command;

use App\Discord\Abstract\AbstractDiscordCommand;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;

class PingCommand extends AbstractDiscordCommand
{
    public function getName(): string
    {
        return 'ping';
    }

    public function getDescription(): string
    {
        return 'Répond "pong!" pour vérifier si le bot est en ligne. En affichant la latence.';
    }

    public function execute(Message $message): void
    {
        $startTime = microtime(true);

        $message->reply('Pong! 🏓')
            ->then(function (Message $response) use ($startTime) {
                $endTime = microtime(true);
                $latency = round(($endTime - $startTime) * 1000);
                $response->edit(MessageBuilder::new()->setContent("Pong! 🏓 (Latence: {$latency}ms)"));
            })
            ->catch(function (\Throwable $e) {
                throw new \LogicException("Impossible d'envoyer le message initial 'pong': {$e->getMessage()}");
            });
    }
}
