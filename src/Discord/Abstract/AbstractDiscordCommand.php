<?php

namespace App\Discord\Abstract;

use Discord\Parts\Channel\Message;

abstract class AbstractDiscordCommand
{
    abstract public function getName(): string;

    abstract public function getDescription(): string;

    abstract public function execute(Message $message): void;

    /**
     * @return string[]
     */
    protected function getArgs(Message $message): array
    {
        $parts = explode(' ', trim($message->content));
        array_shift($parts);

        return $parts;
    }
}
