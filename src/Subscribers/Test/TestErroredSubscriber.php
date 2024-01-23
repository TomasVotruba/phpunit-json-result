<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers\Test;

use LeanBookTools\Subscribers\AbstractSubscriber;
use PHPUnit\Event\Test\Errored;
use PHPUnit\Event\Test\ErroredSubscriber;

final class TestErroredSubscriber extends AbstractSubscriber implements ErroredSubscriber
{
    public function notify(Errored $event): void
    {
        $this->simplePrinter->writeln('E');
        $this->testResultCollector->testErrored($event);
    }
}
