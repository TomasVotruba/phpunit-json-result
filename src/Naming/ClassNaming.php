<?php

declare(strict_types=1);

namespace LeanBookTools\Naming;

final class ClassNaming
{
    /**
     * For input: "App\SomeNamespace\ShortClass"
     * Returns: "Short"
     */
    public static function resolveShortClassName(string $class): string
    {
        $classNameParts = explode('\\', $class);
        $lastKey = array_key_last($classNameParts);

        return $classNameParts[$lastKey];
    }
}
