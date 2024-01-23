<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers\TestRunner;

use LeanBookTools\Subscribers\AbstractSubscriber;
use PHPUnit\Event\TestRunner\Started;
use PHPUnit\Event\TestRunner\StartedSubscriber;
use PHPUnit\Runner\Version;

final class TestRunnerStartedSubscriber extends AbstractSubscriber implements StartedSubscriber
{
    public function notify(Started $event): void
    {
        // starting message is printed by PHPUnit itself
        $this->simplePrinter->writeln('PHPUnit ' . Version::id() . PHP_EOL);
    }
}
