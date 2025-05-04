<?php

namespace App\Tests\Discord\Command;

use App\Discord\Command\PingCommand;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Channel\Message;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use React\Promise\PromiseInterface;

class PingCommandTest extends TestCase
{
    private PingCommand $pingCommand;
    private MockObject $messageMock;
    private MockObject $promiseMock;

    public function testGetName(): void
    {
        self::assertSame('ping', $this->pingCommand->getName());
    }

    public function testGetDescription(): void
    {
        self::assertSame(
            'Répond "pong!" pour vérifier si le bot est en ligne. En affichant la latence.',
            $this->pingCommand->getDescription()
        );
    }

    /**
     * @throws Exception
     */
    public function testExecuteRepliesWithPongAndLatency(): void
    {
        $responseMessageMock = $this->createMock(Message::class);

        $this->promiseMock->expects($this->once())
            ->method('then')
            ->with(
                $this->callback(function (callable $callback) use ($responseMessageMock) {
                    $callback($responseMessageMock);

                    return true;
                })
            )
            ->willReturnSelf();

        $this->promiseMock->expects($this->once())
            ->method('catch');

        $this->messageMock->expects($this->once())
            ->method('reply')
            ->with('Pong! 🏓')
            ->willReturn($this->promiseMock);

        $responseMessageMock->expects($this->once())
            ->method('edit')
            ->with(
                $this->callback(function (MessageBuilder $builder) {
                    $content = $builder->getContent() ?? '';

                    return 1 === preg_match('/^Pong! 🏓 \(Latence: \d+ms\)$/', $content);
                })
            );

        if ($this->messageMock instanceof Message) {
            $this->pingCommand->execute($this->messageMock);
        }
    }

    public function testExecuteHandlesReplyError(): void
    {
        $this->promiseMock->expects($this->once())
            ->method('then')
            ->willReturnSelf();

        $this->promiseMock->expects($this->once())
            ->method('catch')
            ->with(
                $this->callback(function (callable $callback) {
                    $exception = new \RuntimeException('Network error');
                    try {
                        $callback($exception);
                    } catch (\LogicException $e) {
                        $this->assertStringContainsString(
                            "Impossible d'envoyer le message initial 'pong': Network error",
                            $e->getMessage()
                        );

                        return true;
                    }
                    $this->fail('LogicException non levée dans le callback de catch');
                })
            );

        $this->messageMock->expects($this->once())
            ->method('reply')
            ->with('Pong! 🏓')
            ->willReturn($this->promiseMock);

        if ($this->messageMock instanceof Message) {
            $this->pingCommand->execute($this->messageMock);
        }
    }

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->pingCommand = new PingCommand();
        $this->messageMock = $this->createMock(Message::class);
        $this->promiseMock = $this->createMock(PromiseInterface::class);
    }
}
