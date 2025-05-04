<?php

namespace App\Discord\Listener;

use App\Discord\Abstract\AbstractDiscordListener;
use Discord\Discord;
use Discord\Parts\Channel\Channel;
use Discord\Parts\Guild\Guild;
use Discord\Parts\User\Member;
use Discord\Parts\User\User;
use Discord\WebSockets\Event;

class WelcomeListener extends AbstractDiscordListener
{
    private const WELCOME_CHANNEL_NAME = 'accueil';

    public function register(Discord $discord): void
    {
        $discord->on($this->getDiscordEvent(), [$this, 'handleWelcome']);
    }

    public function getDiscordEvent(): string
    {
        return Event::GUILD_MEMBER_ADD;
    }

    public function handleWelcome(Member $member): void
    {
        /** @var ?Guild $guild */
        $guild = $member->guild;

        /** @var ?User $user */
        $user = $member->user;

        if (!$guild instanceof Guild || !$user instanceof User) {
            throw new \LogicException('Impossible de récupérer les informations du serveur ou de l\'utilisateur pour le nouveau membre.');
        }

        /** @var Channel|null $welcomeChannel */
        $welcomeChannel = $guild->channels->find(
            function (Channel $channel) {
                return Channel::TYPE_GUILD_TEXT === $channel->type
                    && strtolower($channel->name ?? '') === strtolower(self::WELCOME_CHANNEL_NAME);
            }
        );

        if ($welcomeChannel instanceof Channel) {
            $message = "👋 Bienvenue sur **{$guild->name}**, <@{$user->id}> ! Amuse-toi bien parmi nous.";

            $nameChannel = $welcomeChannel->name ?? null;

            if (!isset($nameChannel)) {
                $nameChannel = 'canal inconnu';
            }

            $welcomeChannel->sendMessage($message)
                ->catch(function (\Throwable $e) use ($user, $nameChannel) {
                    throw new \LogicException("Impossible d'envoyer le message de bienvenue pour {$user->username} dans #{$nameChannel}.");
                });
        }
    }
}
