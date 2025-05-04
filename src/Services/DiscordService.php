<?php

namespace App\Services;

use App\Discord\Manager\ListenerManager;
use Discord\Discord;
use Discord\WebSockets\Intents;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class DiscordService
{
    private ?Discord $discord = null;

    public function __construct(
        private readonly ListenerManager $listenerManager,
        #[Autowire('%env(DISCORD_BOT_TOKEN)%')]
        private readonly ?string $token = null,
    ) {
    }

    public function run(): void
    {
        if (!isset($this->token)) {
            throw new \LogicException('Impossible de démarrer le bot : token manquant.');
        }

        try {
            $this->discord = new Discord([
                'token' => $this->token,
                'intents' => Intents::getDefaultIntents() | Intents::MESSAGE_CONTENT,
            ]);

            $this->discord->on('ready', function (Discord $discord) {
                $this->listenerManager->registerListeners($discord);
            });

            $this->discord->run();
        } catch (\Exception $e) {
            throw new \LogicException('Erreur lors de l\'initialisation ou de l\'exécution du bot Discord: '.$e->getMessage());
        }
    }

    public function stop(): void
    {
        if ($this->discord instanceof Discord) {
            $this->discord->close();
            $this->discord = null;
        }
    }
}
