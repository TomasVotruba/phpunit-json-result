<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers\Test;

use LeanBookTools\Subscribers\AbstractSubscriber;
use PHPUnit\Event\Test\Passed;
use PHPUnit\Event\Test\PassedSubscriber;

final class TestPassedSubscriber extends AbstractSubscriber implements PassedSubscriber
{
    public function notify(Passed $event): void
    {
        $this->simplePrinter->write('.');
    }
}
