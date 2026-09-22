<?php

declare(strict_types=1);

namespace Mezzio\DebugBar;

use DebugBar\Bridge\Doctrine\DebugBarSQLMiddleware;
use Psr\Container\ContainerInterface;

class DebugBarSQLMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): DebugBarSQLMiddleware
    {
        return new DebugBarSQLMiddleware();
    }
}
