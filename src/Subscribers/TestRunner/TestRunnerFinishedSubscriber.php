<?php

declare(strict_types=1);

namespace TomasVotruba\PHPUnitJsonResultPrinter\Subscribers\TestRunner;

use ECSPrefix202401\Nette\Utils\Strings;
use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Failed;
use PHPUnit\Event\TestRunner\Finished;
use PHPUnit\Event\TestRunner\FinishedSubscriber;
use PHPUnit\Metadata\DataProvider;
use PHPUnit\TestRunner\TestResult\Facade;
use TomasVotruba\PHPUnitJsonResultPrinter\Printer\SimplePrinter;

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
                'tests' => $testResult->numberOfTestsRun(),
                'failed' => $testResult->numberOfTestFailedEvents(),
                'assertions' => $testResult->numberOfAssertions(),
                'errors' => $testResult->numberOfTestErroredEvents(),
                'warnings' => $testResult->numberOfWarnings(),
                'deprecations' => $testResult->numberOfDeprecations(),
                'notices' => $testResult->numberOfNotices(),
                'success' => $testResult->numberOfTestsRun() - $testResult->numberOfTestErroredEvents(),
                'incomplete' => $testResult->numberOfTestMarkedIncompleteEvents(),
                'risky' => $testResult->numberOfTestsWithTestConsideredRiskyEvents(),
                'skipped' => $testResult->numberOfTestSuiteSkippedEvents() + $testResult->numberOfTestSkippedEvents(),
            ],
        ];

        $resultJsonData['failed'] = [];

        // print failed tests
        foreach ($testResult->testFailedEvents() as $testFailedEvent) {
            /** @var Failed $testFailedEvent */
            $testMethod = $testFailedEvent->test();

            /** @var TestMethod $testMethod */
            $failedEventData = [
                'test_class' => $testMethod->className(),
                'test_method' => $testMethod->methodName(),
                'message' => $testFailedEvent->throwable()->message(),
                'exception_class' => $testFailedEvent->throwable()->className(),
                'line' => Strings::after(trim($testFailedEvent->throwable()->stackTrace()), ':', -1),
            ];

            if ($testMethod->testData()->hasDataFromDataProvider()) {
                $failedEventData['data_provider'] = $this->createDataProviderData($testMethod);
            }

            $resultJsonData['failed'][] = $failedEventData;
        }

        $resultJson = json_encode($resultJsonData, JSON_PRETTY_PRINT);
        $this->simplePrinter->writeln($resultJson);
    }

    /**
     * @return array<string, mixed>
     */
    private function createDataProviderData(TestMethod $testMethod): array
    {
        $dataFromDataProvider = $testMethod->testData()->dataFromDataProvider();

        $dataProviderData = [
            'key' => $dataFromDataProvider->dataSetName(),
            'data' => $dataFromDataProvider->data(),
        ];

        foreach ($testMethod->metadata() as $metadata) {
            if ($metadata instanceof DataProvider) {
                $dataProviderData['provider_method'] = $metadata->methodName();
            }
        }

        return $dataProviderData;
    }
}
