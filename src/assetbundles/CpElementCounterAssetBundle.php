<?php

/**
 * CP Element Count plugin for Craft CMS 3.x
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2018 André Elvan
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
