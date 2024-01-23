<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers\Test;

use LeanBookTools\Subscribers\AbstractSubscriber;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\Test\FailedSubscriber;

final class TestFailedSubscriber extends AbstractSubscriber implements FailedSubscriber
{
    public function notify(Failed $event): void
    {
        $this->simplePrinter->write('F');
        $this->testResultCollector->testFailed($event);
    }
}
