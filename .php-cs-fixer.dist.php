<?php

$rules = [
    '@PSR2' => true,

    'array_indentation' => true,
    'array_syntax' => ['syntax' => 'short'],
    'binary_operator_spaces' => ['default' => 'single_space',],
    'concat_space' => ['spacing' => 'one',],
    'echo_tag_syntax' => ['format' => 'long'],
    'multiline_whitespace_before_semicolons' => true,
    'no_extra_blank_lines' => true,
    'no_unused_imports' => true,
    'not_operator_with_successor_space' => false,
    'ordered_imports' => ['sort_algorithm' => 'alpha',],
];

$finder = PhpCsFixer\Finder::create()
    ->exclude('app/cache')
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
$config->setRules($rules)->setFinder($finder);

return $config;
