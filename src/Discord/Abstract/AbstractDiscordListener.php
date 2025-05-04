<?php

namespace App\Discord\Abstract;

use Discord\Discord;

abstract class AbstractDiscordListener
{
    abstract public function register(Discord $discord): void;

    abstract public function getDiscordEvent(): string;
}
