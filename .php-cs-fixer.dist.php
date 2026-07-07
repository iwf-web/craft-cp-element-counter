<?php declare(strict_types=1);

require_once __DIR__.'/vendor/autoload.php';

use IWFWeb\CodingStandard\IWFWebStandardRiskySet;
use IWFWeb\CodingStandard\IWFWebStandardSet;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

// Copyright years + holder come from LICENSE.txt (single source of truth); the
// author email from composer.json — so the header never drifts from them.
preg_match(
    '/Copyright \(c\) ([0-9][0-9,\s-]*[0-9])\s+(.+)/',
    (string) file_get_contents(__DIR__.'/LICENSE.txt'),
    $copyright,
);
[, $years, $name] = $copyright;
$email = 'web-solutions@iwf.ch';

$header = <<<EOF
    CP Element Counter plugin

    @package   CraftCPElementCounter
    @author    {$name} <{$email}>
    @copyright Copyright (c) {$years} {$name} <{$email}>
    @license   https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt MIT License
    @link      https://github.com/iwf-web/craft-cp-element-counter
    EOF;

// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/ruleSets/index.rst
// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/doc/rules/index.rst
return (new Config())
    ->registerCustomRuleSets([
        new IWFWebStandardSet(),
        new IWFWebStandardRiskySet(),
    ])
    ->setFinder(Finder::create()
        ->in(__DIR__)
        ->ignoreDotFiles(false)
        ->ignoreVCSIgnored(true),
    )
    ->setUnsupportedPhpVersionAllowed(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@IWFWeb/standard' => true,
        '@IWFWeb/standard:risky' => true,
        'header_comment' => [
            'comment_type' => 'PHPDoc',
            'header' => $header,
        ],
    ])
;

// @php-cs-fixer-ignore header_comment
