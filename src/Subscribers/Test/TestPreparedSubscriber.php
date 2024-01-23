<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers\Test;

use LeanBookTools\Subscribers\AbstractSubscriber;
use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber;

final class TestPreparedSubscriber extends AbstractSubscriber implements PreparedSubscriber
{
    // set default status value
    public function notify(Prepared $event): void
    {
        $this->testResultCollector->testPrepared($event);
    }
}
