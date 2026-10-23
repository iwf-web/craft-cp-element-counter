<?php declare(strict_types=1);

/**
 * CP Element Counter plugin
 *
 * @package   CraftCPElementCounter
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2024-2026 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-cp-element-counter
 */

namespace cpelementcounter;

use cpelementcounter\services\CountService;
use CraftCms\Cms\Plugin\Plugin;
use Inertia\Inertia;

/**
 * Control Panel Element Counter plugin.
 *
 * Shows the number of entries, assets and users per source next to the
 * source in the control panel navigation.
 *
 * @author IWF <s.friedrich@iwf.ch>
 * @author André Elvan (thanks!)
 */
class CpElementCounter extends Plugin
{
    public string $schemaVersion = '1.0.0';

    /**
     * @var array<string, string>
     */
    protected array $scripts = [
        __DIR__.'/resources/cpelementcounter.js' => 'cpelementcounter.js',
    ];

    /**
     * @var array<string, string>
     */
    protected array $styles = [
        __DIR__.'/resources/cpelementcounter.css' => 'cpelementcounter.css',
    ];

    public function register(): void
    {
        $this->app->singleton(CountService::class);
    }

    public function boot(): void
    {
        // Lazy, so the counts are only queried when a CP page actually renders.
        Inertia::share('cpElementCounter', fn (): object => (object) $this->app->make(CountService::class)->getCountsByCpUrl());
    }
}
