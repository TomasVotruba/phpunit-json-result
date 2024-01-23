<?php

declare(strict_types=1);

namespace LeanBookTools\Subscribers;

use LeanBookTools\Printer\SimplePrinter;
use PHPUnit\Logging\TestDox\TestResultCollector;

abstract class AbstractSubscriber
{
    public function __construct(
        protected readonly SimplePrinter $simplePrinter,
        protected readonly TestResultCollector $testResultCollector,
    ) {
    }
}
