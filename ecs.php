<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;

return ECSConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withRootFiles()
    ->withPreparedSets(
        psr12: true,
        common: true,
        cleanCode: true,
        symplify: true,
        strict: true,
    )// configure cache paths and namespace - useful e.g. Gitlab CI caching, where getcwd() produces always different path
    ->withCache(
        directory: sys_get_temp_dir() . '/_changed_files_detector_tests',
        namespace: getcwd() // normalized to directory separator
    )

    // print contents with specific indent rules
    ->withSpacing(indentation: Option::INDENTATION_SPACES, lineEnding: PHP_EOL)

    // modify parallel run
    ->withParallel(timeoutSeconds: 120, maxNumberOfProcess: 32, jobSize: 20);



