<?php

declare(strict_types=1);

namespace TomasVotruba\PHPUnitJsonResultPrinter\Printer;

use PHPUnit\TextUI\Output\Printer;

final readonly class SimplePrinter
{
    public function __construct(
        private Printer $phpunitPrinter
    ) {
    }

    public function writeln(string $content): void
    {
        $this->phpunitPrinter->print($content . PHP_EOL);
    }
}
