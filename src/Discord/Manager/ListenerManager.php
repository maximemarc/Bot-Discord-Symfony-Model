<?php

namespace App\Discord\Manager;

use App\Discord\Abstract\AbstractDiscordListener;
use Discord\Discord;

readonly class ListenerManager
{
	/**
	 * @param  iterable<AbstractDiscordListener>  $listeners
	 */
	public function __construct(private iterable $listeners) { }

	public function registerListeners(Discord $discordClient): void
	{
		$count = 0;
		foreach ($this->listeners as $listener) {
			try {
				$listener->register($discordClient);
				++$count;
			} catch (\Throwable $e) {
				throw new \LogicException("Erreur lors de l\'enregistrement de l\'écouteur: {$e->getMessage()}");
			}
		}
	}
}
