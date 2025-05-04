<?php

namespace App\Command;

use App\Services\DiscordService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:run-discord-bot',
    description: 'Lance le bot Discord',
)]
class RunDiscordBotCommand extends Command
{
    public function __construct(private readonly DiscordService $discordService)
    {
        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Démarrage du Bot Discord');

        $signalHandler = function (int $signal) use ($io) {
            $io->writeln('');
            $io->warning('Signal reçu ('.$signal.'), arrêt du bot...');
            $this->discordService->stop();
        };

        pcntl_async_signals(true);
        pcntl_signal(SIGINT, $signalHandler);
        pcntl_signal(SIGTERM, $signalHandler);

        try {
            $this->discordService->run();
            $io->success('Le bot Discord s\'est arrêté proprement.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'exécution du bot: '.$e->getMessage());

            return Command::FAILURE;
        }
    }
}
