<?php

namespace App\Discord\Manager;

use App\Discord\Abstract\AbstractDiscordCommand;
use Discord\Parts\Channel\Message;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

readonly class CommandManager
{
	/**
	 * @param  iterable<AbstractDiscordCommand>  $commands
	 */
	public function __construct(
		#[Autowire('app.discord_command')]
		private iterable $commands,
		#[Autowire('%app.discord_command_prefix%')]
		private string $commandPrefix,
	)
	{
	}

	public function handleCommand(Message $message): void
	{
		if (
			true === $message->author?->bot
			|| !str_starts_with($message->content, $this->commandPrefix)
		) {
			return;
		}

		$contentWithoutPrefix = substr($message->content, strlen($this->commandPrefix));
		$parts = explode(' ', trim($contentWithoutPrefix));
		$commandName = strtolower($parts[0]);

		if (empty($commandName)) {
			return;
		}

		/** @var AbstractDiscordCommand $command */
		foreach ($this->commands as $command) {
			if (0 === strcasecmp($command->getName(), $commandName)) {
				try {
					$command->execute($message);

					return;
				} catch (\Throwable $e) {
					$message
						->reply("Oups ! Une erreur est survenue lors de l'exécution de la commande `{$commandName}`.")
						->catch(
							function (\Throwable $e): void {
								throw new \LogicException(
									"Impossible d'envoyer le message d'erreur à l'utilisateur: {$e->getMessage()}"
								);
							}
						);
					throw new \LogicException("Impossible d'exécuter la commande `{$commandName}`: {$e->getMessage()}");
				}
			}
		}
	}
}
