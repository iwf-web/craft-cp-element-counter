<?php

/**
 * CP Element Counter plugin for Craft CMS 5.x
 *
 * @link      https://www.iwf.ch/web-solutions
 * @copyright Copyright (c) 2024 Stefan Friedrich
 */

namespace cpelementcounter\services;

use Craft;
use craft\base\Component;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use craft\models\CategoryGroup;
use craft\services\UserGroups;

/**
 * CpElementCounterService Service
 *
 * @author    Stefan Friedrich
 * @package   CpElementCounter
 * @since     1.0.0
 */
class CountService extends Component
{
    public function getEntriesCount($uids = []): array
    {
        if (count($uids) === 0) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        foreach ($uids as $uid) {
            $section = Craft::$app->getEntries()->getSectionByUid($uid);
            $count = Entry::find()->sectionId($section->id)->limit(null)->status(['disabled', 'enabled'])->count();
            $r[$uid] = $count;
            $totalCount = $totalCount + $count;
        }

        //$count = Entry::find()->limit(null)->status(['disabled', 'enabled'])->count();
        $r['*'] = $totalCount;

        return $r;
    }

    public function getCategoriesCount($uids = []): array
    {
        if (count($uids) === 0) {
            return [];
        }

        $r = [];

        foreach ($uids as $uid) {

            $categoryGroup = Craft::$app->getCategories()->getGroupByUid($uid);
            $count = \craft\elements\Category::find()
                ->groupId($categoryGroup->id)
                ->limit(null)
                ->status(['disabled', 'enabled'])
                ->count();
            $r[$uid] = $count;
        }

        return $r;
    }

    public function getUsersCount($uids = []): array
    {
        if (count($uids) === 0) {
            return [];
        }

        $r = [];

        foreach ($uids as $uid) {
            $group = Craft::$app->getUserGroups()->getGroupByUid($uid);
            $count = User::find()->groupId($group->id)->limit(null)->status(null)->count();
            $r[$uid] = $count;
        }
        
        $count = User::find()->limit(null)->status(null)->count();
        $r['*'] = $count;

        $count = User::find()->admin(true)->limit(null)->status(null)->count();
        $r['admins'] = $count;

        return $r;
    }

    public function getAssetsCount($folders = []): array
    {
        if (count($folders) === 0) {
            return [];
        }

        $r = [];

        foreach ($folders as $folder) {
            $arr = explode('|', $folder);
            $count = Asset::find()->folderId($arr[count($arr)-1])->includeSubfolders(true)->limit(null)->count();
            $r[$folder] = $count;
        }
        
        return $r;
    }

}
