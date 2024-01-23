<?php

declare(strict_types=1);

namespace TomasVotruba\PHPUnitJsonResultPrinter\Test;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class OutputCleanerTest extends TestCase
{
    #[DataProvider('provideData')]
    public function testSame(string $input): void
    {
        $this->assertSame(100, $input);
    }

    public static function provideData(): Iterator
    {
        yield [
            'not equal',
            100,
        ];
    }
}
