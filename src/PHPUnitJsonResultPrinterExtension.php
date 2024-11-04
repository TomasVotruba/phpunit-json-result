<?php

declare(strict_types=1);

namespace TomasVotruba\PHPUnitJsonResultPrinter;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnit\TextUI\Output\DefaultPrinter;
use TomasVotruba\PHPUnitJsonResultPrinter\Printer\SimplePrinter;
use TomasVotruba\PHPUnitJsonResultPrinter\Subscribers\TestRunner\TestRunnerFinishedSubscriber;

/**
 * Registered in phpunit.xml
 */
final class PHPUnitJsonResultPrinterExtension implements Extension
{
    private readonly SimplePrinter $simplePrinter;

    public function __construct()
    {
        $this->simplePrinter = new SimplePrinter(DefaultPrinter::standardOutput());
    }

    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        if ($configuration->noOutput()) {
            return;
        }

        // very important to replace output with ours
        $facade->replaceOutput();

        $facade->registerSubscribers(
            new TestRunnerFinishedSubscriber($this->simplePrinter),
        );
    }
}
