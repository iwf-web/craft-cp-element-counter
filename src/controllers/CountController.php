<?php

/**
 * CP Element Count plugin for Craft CMS 3.x
 *
 * @link      https://www.vaersaagod.no/
 * @copyright Copyright (c) 2018 André Elvan
 */

namespace cpelementcounter\controllers;

use Craft;
use craft\elements\Asset;
use craft\web\Controller;

use cpelementcounter\CpElementCounter as Plugin;

/**
 * Class CountController
 *
 * @package cpelementcounter\controllers
 */
class CountController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var array
     */
    protected int|bool|array $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionGetEntriesCount(): \yii\web\Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);
        
        $counts = Plugin::$plugin->count->getEntriesCount($uids);
        
        return $this->asJson($counts);
    }

    public function actionGetCategoriesCount(): \yii\web\Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);
        
        $counts = Plugin::$plugin->count->getCategoriesCount($uids);
        
        return $this->asJson($counts);
    }

    public function actionGetUsersCount(): \yii\web\Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);
        
        $counts = Plugin::$plugin->count->getUsersCount($uids);
        
        return $this->asJson($counts);
    }

    public function actionGetAssetsCount(): \yii\web\Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = Craft::$app->getRequest();
        $folders = $request->getParam('folders', []);
        
        $counts = Plugin::$plugin->count->getAssetsCount($folders);
        
        return $this->asJson($counts);
    }
}
