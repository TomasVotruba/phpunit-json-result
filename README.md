# Cleaner Result Printer

## Install

```bash
composer require --dev tomasvotruba/phpunit-json-result-printer
```

## Usage

Register extension in your `phpunit.xml` file:

```xml
<extensions>
    <bootstrap class="TomasVotruba\PHPUnitJsonResultPrinter\PHPUnitJsonResultPrinterExtension" />
</extensions>
```
