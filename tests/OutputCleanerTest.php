<?php

declare(strict_types=1);

namespace LeanBookTools\Test;

use Iterator;
use LeanBookTools\OutputCleaner;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OutputCleanerTest extends TestCase
{
    /**
     * Note: data provider is useful on purpose to test this extension
     * and cleaned output as Rector uses data providers by default
     */
    #[DataProvider('provideCleanUpExceptionMessageData')]
    public function testCleanUpExceptionMessage(string $inputFile, string $expectedFile): void
    {
        /** @var string $inputFileContents */
        $inputFileContents = file_get_contents($inputFile);

        $cleanedFileContents = OutputCleaner::cleanUpExceptionMessage($inputFileContents);

        $this->assertStringEqualsFile($expectedFile, $cleanedFileContents);
    }

    public static function provideCleanUpExceptionMessageData(): Iterator
    {
        yield [
            __DIR__ . '/Fixture/input_content.php.inc',
            __DIR__ . '/Fixture/expected_content.php.inc',
        ];
    }
}
