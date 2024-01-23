<?php

declare(strict_types=1);

namespace TomasVotruba\PHPUnitJsonResultPrinter\Subscribers\TestRunner;

use TomasVotruba\PHPUnitJsonResultPrinter\Printer\SimplePrinter;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\TestRunner\Finished;
use PHPUnit\Event\TestRunner\FinishedSubscriber;
use PHPUnit\TestRunner\TestResult\Facade;
use PHPUnit\TextUI\Output\DefaultPrinter;
use PHPUnit\TextUI\Output\SummaryPrinter;

final class TestRunnerFinishedSubscriber implements FinishedSubscriber
{
    public function __construct(
        private SimplePrinter $simplePrinter
    ) {
    }

    public function notify(Finished $event): void
    {
        $testResult = Facade::result();

        $resultJsonData = [
            'counts' => [
                'number_of_test_run' => $testResult->numberOfTestsRun(),
                'error_test_run' => $testResult->numberOfTestErroredEvents(),
                'success_test_run' => $testResult->numberOfTestsRun() - $testResult->numberOfTestErroredEvents(),

            ],
        ];

        $resultJson = json_encode($resultJsonData, JSON_PRETTY_PRINT);
        $this->simplePrinter->writeln($resultJson);

        // simple progress report
        if ($testResult->numberOfTestsRun() !== 0) {
            $successTestCount = $testResult->numberOfTestsRun() - $testResult->numberOfTestErroredEvents();
        }

//        // print failed tests
//        if ($testResult->hasTestFailedEvents()) {
//            $this->printListHeaderWithNumber($testResult->numberOfTestFailedEvents(), 'failure');
//            $this->printTestFailedEvents($testResult->testFailedEvents());
//        }
//
//        // print in JSON
//        $summaryPrinter = new SummaryPrinter(DefaultPrinter::standardOutput(), false);
//        $summaryPrinter->print($testResult);
    }

    /**
     * @param Failed[] $testFailedEvents
     */
    private function printTestFailedEvents(array $testFailedEvents): void
    {
        $i = 1;

        foreach ($testFailedEvents as $testFailedEvent) {
            $title = $this->createTitle($testFailedEvent->test());
            $body = $testFailedEvent->throwable()->asString();

            $this->printListElement($i, $title, $body);
            $i++;
        }
    }

    /**
     * Mimics
     * @see \PHPUnit\TextUI\Output\Default\ResultPrinter::printListElement()
     */
    private function printListElement(int $number, string $title, string $body): void
    {
        $body = trim($body);

        $this->simplePrinter->writeln(
            sprintf(
                "%s%d) %s\n%s%s",
                $number > 1 ? "\n" : '',
                $number,
                $title,
                $body,
                ! empty($cleanBody) ? "\n" : '',
            ),
        );
    }

    /**
     * Mimics
     * @see \PHPUnit\TextUI\Output\Default\ResultPrinter::name
     *
     * The result should be short class name and fixture number e.g. "MigrateToDateTimeImmutableRectorTest::test with data set #1"
     */
    private function createTitle(Test $test): string
    {
        if (! $test instanceof TestMethod) {
            return $test->name();
        }

        $shortClassName = ClassNaming::resolveShortClassName($test->className());

        $title = $shortClassName . '::' . $test->methodName();

        if ($test->testData()->hasDataFromDataProvider()) {
            $dataFromDataProvider = $test->testData()->dataFromDataProvider();
            $title .= ' with data set #' . $dataFromDataProvider->dataSetName();
        }

        return $title;
    }
}
