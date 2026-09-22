<?php

declare(strict_types=1);

namespace Ikoss\Mezzio\DebugBar\Tests;

use DebugBar\DebugBar;
use DebugBar\DebugBarException;
use DebugBar\StandardDebugBar;
use Ikoss\Mezzio\DebugBar\OpenHandler;
use Ikoss\Mezzio\DebugBar\OpenHandlerFactory;
use Ikoss\Mezzio\DebugBar\Tests\Storage\MockStorage;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class OpenHandlerFactoryTest extends TestCase
{
    public DebugBar $debugbar;

    public MockStorage $storage;

    public function setUp(): void
    {
        $this->debugbar = $this->createStub(DebugBar::class);

        $this->storage = new MockStorage(['storage' => ['__meta' => ['id' => 'Xstorage']]]);
    }

    public function testFactoryWillThrowExeceptionIfDebugbarStorogeIsNull(): void
    {
        $this->expectException(DebugBarException::class);

        $container = $this->createStub(ContainerInterface::class);
        $container
            ->method('has')
            ->willReturnMap([
                [DebugBar::class, true],
            ]);
        $container
            ->method('get')
            ->willReturnMap([
                [DebugBar::class, $this->debugbar],
            ]);

        $factory     = new OpenHandlerFactory();
        $openHandler = $factory($container);
    }

    public function testFactory(): void
    {
        $container = $this->createStub(ContainerInterface::class);
        $debugBar  = (new StandardDebugBar())->setStorage($this->storage);
        $container
            ->method('has')
            ->willReturnMap([
                [DebugBar::class, true],
            ]);

        $container
            ->method('get')
            ->willReturnMap([
                [DebugBar::class, $debugBar],
            ]);

        $factory     = new OpenHandlerFactory();
        $openHandler = $factory($container);

        self::assertInstanceOf(OpenHandler::class, $openHandler);
    }
}
