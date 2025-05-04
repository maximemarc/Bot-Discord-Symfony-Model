<?php

namespace App\Tests\Command;

use App\Services\DiscordService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class RunDiscordBotCommandTest extends KernelTestCase
{
	private MockObject $discordServiceMock;
	private CommandTester $commandTester;

	public function testExecuteAndStopWithSignal(): void
	{
		$this->discordServiceMock->expects($this->once())
			->method('run')
			->willReturnCallback(function () {
				if (function_exists('posix_kill') && function_exists('posix_getpid')) {
					posix_kill(posix_getpid(), SIGINT);
				}
			});

		$this->discordServiceMock->expects($this->once())
			->method('stop');

		$statusCode = $this->commandTester->execute([]);

		self::assertSame(Command::SUCCESS, $statusCode);

		$output = $this->commandTester->getDisplay();
		self::assertStringContainsString('Démarrage du Bot Discord', $output);
		self::assertStringContainsString('Signal reçu (2), arrêt du bot...', $output);
		self::assertStringContainsString('Le bot Discord s\'est arrêté proprement.', $output);
	}

	public function testExecuteWithError(): void
	{
		$this->discordServiceMock->expects($this->once())
			->method('run')
			->willThrowException(new \LogicException('Test error'));

		$this->discordServiceMock->expects($this->never())
			->method('stop');

		$statusCode = $this->commandTester->execute([]);

		self::assertSame(Command::FAILURE, $statusCode);

		$output = $this->commandTester->getDisplay();
		self::assertStringContainsString('[ERROR] Erreur lors de l\'exécution du bot: Test error', $output);
	}

	/**
	 * @throws Exception
	 */
	protected function setUp(): void
	{
		self::bootKernel();
		$this->discordServiceMock = $this->createMock(DiscordService::class);

		$application = new Application(self::$kernel);

		self::$kernel->getContainer()->set(DiscordService::class, $this->discordServiceMock);

		$command = $application->find('app:run-discord-bot');

		$this->commandTester = new CommandTester($command);
	}
}
