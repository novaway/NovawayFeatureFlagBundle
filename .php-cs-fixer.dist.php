<?php

$header = <<<EOF
This file is part of the NovawayFeatureFlagBundle package.
(c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'global_namespace_import' => false,
        'header_comment' => ['header' => $header],
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'phpdoc_summary' => false,
    ])
    ->setUsingCache(true)
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    )
;
