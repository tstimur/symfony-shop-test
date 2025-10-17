<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder())
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
    ->exclude([
        'var',
        'migrations',
        'vendor',
    ])
    ->append([
        __FILE__,
        __DIR__.'/bin/console',
    ]);

$config = (new Config())
    ->setCacheFile(__DIR__.'/var/.php-cs-fixer.cache')
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        // Правила
        '@PSR12' => true,
        '@Symfony' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'final_class' => false,
        'use_arrow_functions' => false,
        'declare_strict_types' => true,
        'strict_comparison' => true,
    ]);

return $config;
