<?php

declare(strict_types=1);

namespace LeanBookTools\Printer;

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

    public function write(string $content): void
    {
        $this->phpunitPrinter->print($content);
    }

    public function newLine(int $count = 1): void
    {
        $this->write(str_repeat(PHP_EOL, $count));
    }
}
