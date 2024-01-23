<?php

declare(strict_types=1);

namespace LeanBookTools;

use LeanBookTools\Printer\SimplePrinter;
use LeanBookTools\Subscribers\Test\TestErroredSubscriber;
use LeanBookTools\Subscribers\Test\TestFailedSubscriber;
use LeanBookTools\Subscribers\Test\TestFinishedSubscriber;
use LeanBookTools\Subscribers\Test\TestPassedSubscriber;
use LeanBookTools\Subscribers\Test\TestPreparedSubscriber;
use LeanBookTools\Subscribers\TestRunner\TestRunnerFinishedSubscriber;
use LeanBookTools\Subscribers\TestRunner\TestRunnerStartedSubscriber;
use PHPUnit\Event\Facade as EventFacade;
use PHPUnit\Logging\TestDox\TestResultCollector;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;

/**
 * Registered in phpunit.xml
 */
final class CleanerResultPrinterExtension implements Extension
{
    private SimplePrinter $simplePrinter;

    private TestResultCollector $testResultCollector;

    public function __construct()
    {
        $this->simplePrinter = new SimplePrinter(DefaultPrinter::standardOutput());
        $this->testResultCollector = new TestResultCollector(new EventFacade());
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        // very important to replace output with ours
        $facade->replaceOutput();

        $facade->registerSubscribers(
            // single test
            new TestPreparedSubscriber($this->simplePrinter, $this->testResultCollector),
            new TestFailedSubscriber($this->simplePrinter, $this->testResultCollector),
            new TestErroredSubscriber($this->simplePrinter, $this->testResultCollector),
            new TestFinishedSubscriber($this->simplePrinter, $this->testResultCollector),
            new TestPassedSubscriber($this->simplePrinter, $this->testResultCollector),

            // test runner
            new TestRunnerStartedSubscriber($this->simplePrinter, $this->testResultCollector),
            new TestRunnerFinishedSubscriber($this->simplePrinter, $this->testResultCollector),
        );
    }
}
