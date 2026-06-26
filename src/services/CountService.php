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

namespace cpelementcounter\services;

use craft\base\Component;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use verbb\events\elements\Event;
use verbb\events\Events;

/**
 * CpElementCounterService Service.
 *
 * @author    Stefan Friedrich
 *
 * @since     1.0.0
 */
class CountService extends Component
{
    public function getEntriesCount($uids = []): array
    {
        if (\count($uids) === 0) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        $currentSiteHandle = \Craft::$app->request->getParam('site') ?? \Craft::$app->getSites()->getCurrentSite()->handle;
        $site = \Craft::$app->sites->getSiteByHandle($currentSiteHandle, true);

        foreach ($uids as $uid) {
            $section = \Craft::$app->getEntries()->getSectionByUid($uid);
            $count = Entry::find()->sectionId($section->id)->siteId($site->id)->limit(null)->status(['disabled', 'enabled'])->count();
            $r[$uid] = $count;
            $totalCount += $count;
        }

        // $count = Entry::find()->limit(null)->status(['disabled', 'enabled'])->count();
        $r['*'] = $totalCount;

        return $r;
    }

    public function getCategoriesCount($uids = []): array
    {
        if (\count($uids) === 0) {
            return [];
        }

        $r = [];

        foreach ($uids as $uid) {
            $categoryGroup = \Craft::$app->getCategories()->getGroupByUid($uid);
            $count = Category::find()
                ->groupId($categoryGroup->id)
                ->limit(null)
                ->status(['disabled', 'enabled'])
                ->count()
            ;
            $r[$uid] = $count;
        }

        return $r;
    }

    public function getUsersCount($uids = []): array
    {
        if (\count($uids) === 0) {
            return [];
        }

        $r = [];

        foreach ($uids as $uid) {
            $group = \Craft::$app->getUserGroups()->getGroupByUid($uid);
            $count = User::find()->groupId($group->id)->limit(null)->status(null)->count();
            $r[$uid] = $count;
        }

        $count = User::find()->limit(null)->status(null)->count();
        $r['*'] = $count;

        $count = User::find()->admin(true)->limit(null)->status(null)->count();
        $r['admins'] = $count;

        return $r;
    }

    public function getEventsCount($uids = []): array
    {
        if (\count($uids) === 0) {
            return [];
        }

        // Soft dependency on verbb/events — only count if the plugin is installed.
        if (!class_exists(Event::class)) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        foreach ($uids as $uid) {
            $eventType = Events::$plugin->getEventTypes()->getEventTypeByUid($uid);
            if ($eventType === null) {
                continue;
            }

            $count = Event::find()
                ->typeId($eventType->id)
                ->limit(null)
                ->status(['disabled', 'enabled'])
                ->count()
            ;

            $r[$uid] = $count;
            $totalCount += $count;
        }

        $r['*'] = $totalCount;

        return $r;
    }

    public function getAssetsCount($folders = []): array
    {
        if (\count($folders) === 0) {
            return [];
        }

        $r = [];

        foreach ($folders as $folder) {
            $arr = explode('|', $folder);
            $count = Asset::find()->folderId($arr[\count($arr) - 1])->includeSubfolders(true)->limit(null)->count();
            $r[$folder] = $count;
        }

        return $r;
    }
}
