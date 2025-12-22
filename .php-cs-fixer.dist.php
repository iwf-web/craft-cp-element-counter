<?php declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use IWF\CodingStandard\IWFRiskySet;
use IWF\CodingStandard\IWFSet;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$year = date('Y');
$header = <<<EOF
    CP Element Counter plugin

    @package   CraftCPElementCounter
    @author    IWF Web Solutions <web-solutions@iwf.ch>
    @copyright Copyright (c) 2024-{$year} IWF Web Solutions <web-solutions@iwf.ch>
    @license   https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt MIT License
    @link      https://github.com/iwf-web/craft-cp-element-counter
    EOF;

// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst
// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new Config())
    ->registerCustomRuleSets([
        new IWFSet(),
        new IWFRiskySet(),
    ])
    ->setFinder(Finder::create()
        ->in(__DIR__)
        ->ignoreDotFiles(false)
        ->ignoreVCSIgnored(true)
        ->notPath('.php-cs-fixer.dist.php')
    )
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setUnsupportedPhpVersionAllowed(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@IWF/standard' => true,
        '@IWF/standard:risky' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
        ],
    ])
;
