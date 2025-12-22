<?php declare(strict_types=1);

/**
 * CP Element Counter plugin
 *
 * @package   CraftCPElementCounter
 * @author    IWF Web Solutions <web-solutions@iwf.ch>
 * @copyright Copyright (c) 2024-2025 IWF Web Solutions <web-solutions@iwf.ch>
 * @license   https://github.com/iwf-web/craft-cp-element-counter/blob/main/LICENSE.txt MIT License
 * @link      https://github.com/iwf-web/craft-cp-element-counter
 */

namespace cpelementcounter\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class CpElementCounterAssetBundle extends AssetBundle
{
    public function init(): void
    {
        $this->sourcePath = '@cpelementcounter/resources';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'cpelementcounter.js',
        ];

        $this->css = [
            'cpelementcounter.css',
        ];

        parent::init();
    }
}
