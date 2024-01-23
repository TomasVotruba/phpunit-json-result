<?php

declare(strict_types=1);

namespace LeanBookTools;

/**
 * @see \LeanBookTools\Test\OutputCleanerTest
 */
final class OutputCleaner
{
    public static function cleanUpExceptionMessage(string $message): string
    {
        // remove directory from file paths
        $lines = explode("\n", $message);
        foreach ($lines as $key => $line) {
            // remove the line with vendor/phpunit reference, not needed
            if (str_contains($line, 'vendor/phpunit')) {
                unset($lines[$key]);
                continue;
            }

            $result = preg_match('/(.+\.(php|php\.inc))/', $line, $matches);
            if ($result === 0) {
                continue;
            }

            $filePath = $matches[0];
            $simplifiedFilePath = pathinfo($filePath, PATHINFO_BASENAME);
            $lines[$key] = str_replace($filePath, $simplifiedFilePath, $line);
        }

        return implode("\n", $lines);
    }
}
