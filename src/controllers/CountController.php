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

namespace cpelementcounter\controllers;

use cpelementcounter\CpElementCounter as Plugin;
use craft\web\Controller;
use yii\web\Response;

class CountController extends Controller
{
    // Protected Properties
    // =========================================================================

    /**
     * @var array
     */
    protected array|bool|int $allowAnonymous = true;

    // Public Methods
    // =========================================================================

    public function actionGetEntriesCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);

        $counts = Plugin::$plugin->count->getEntriesCount($uids);

        return $this->asJson($counts);
    }

    public function actionGetCategoriesCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);

        $counts = Plugin::$plugin->count->getCategoriesCount($uids);

        return $this->asJson($counts);
    }

    public function actionGetUsersCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);

        $counts = Plugin::$plugin->count->getUsersCount($uids);

        return $this->asJson($counts);
    }

    public function actionGetEventsCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $uids = $request->getParam('uids', []);

        $counts = Plugin::$plugin->count->getEventsCount($uids);

        return $this->asJson($counts);
    }

    public function actionGetCartsCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $keys = $request->getParam('keys', []);

        $counts = Plugin::$plugin->count->getCartsCount($keys);

        return $this->asJson($counts);
    }

    public function actionGetSubmissionsCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $formIds = $request->getParam('formIds', []);

        $counts = Plugin::$plugin->count->getSubmissionsCount($formIds);

        return $this->asJson($counts);
    }

    public function actionGetSentNotificationsCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $formIds = $request->getParam('formIds', []);

        $counts = Plugin::$plugin->count->getSentNotificationsCount($formIds);

        return $this->asJson($counts);
    }

    public function actionGetAssetsCount(): Response
    {
        $config = Plugin::$plugin->getSettings();
        $request = \Craft::$app->getRequest();
        $folders = $request->getParam('folders', []);

        $counts = Plugin::$plugin->count->getAssetsCount($folders);

        return $this->asJson($counts);
    }
}
