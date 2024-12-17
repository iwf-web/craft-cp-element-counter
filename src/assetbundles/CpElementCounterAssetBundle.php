<?php

/**
 * CP Element Counter plugin for Craft CMS 5.x
 *
 * @link      https://www.iwf.ch/web-solutions
 * @copyright Copyright (c) 2024 Stefan Friedrich
 */

namespace cpelementcounter\assetbundles;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class CpElementCounterAssetBundle extends AssetBundle
{

    public function init()
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
