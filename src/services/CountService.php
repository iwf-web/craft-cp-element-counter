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
use craft\commerce\elements\Order;
use craft\commerce\Plugin as CommercePlugin;
use craft\elements\Asset;
use craft\elements\Category;
use craft\elements\Entry;
use craft\elements\User;
use verbb\events\elements\Event;
use verbb\events\Events;
use verbb\formie\elements\SentNotification;
use verbb\formie\elements\Submission;

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

    /**
     * @param string[] $uids
     *
     * @return array<string, int>
     */
    public function getEventsCount(array $uids = []): array
    {
        if (\count($uids) === 0) {
            return [];
        }

        // Soft dependency on verbb/events — the class can exist without the
        // plugin being installed, so the instance is what decides.
        if (!class_exists(Event::class)) {
            return [];
        }

        $events = Events::$plugin;
        if ($events === null) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        foreach ($uids as $uid) {
            $eventType = $events->getEventTypes()->getEventTypeByUid($uid);
            if ($eventType === null) {
                continue;
            }

            $count = (int) Event::find()
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

    /**
     * Counts orders for the cart sources rendered by craft\commerce on the orders index.
     * Keys are passed in full (e.g. `carts:active:default`, `carts:inactive:default`,
     * `carts:attempted-payment:default`) so the caller doesn't need to know the
     * Commerce store handle.
     *
     * @param string[] $keys
     *
     * @return array<string, int>
     */
    public function getCartsCount(array $keys = []): array
    {
        if (\count($keys) === 0) {
            return [];
        }

        // Soft dependency on craft\commerce — the class can exist without the
        // plugin being installed, so the instance is what decides.
        if (!class_exists(Order::class)) {
            return [];
        }

        $commerce = CommercePlugin::getInstance();
        if ($commerce === null) {
            return [];
        }

        $r = [];
        $edge = $commerce->getCarts()->getActiveCartEdgeDuration();

        foreach ($keys as $key) {
            // Expected shape: "carts:<type>:<storeHandle>"
            $parts = explode(':', $key);
            if (\count($parts) !== 3 || $parts[0] !== 'carts') {
                continue;
            }
            [$_, $type, $storeHandle] = $parts;

            $store = $commerce->getStores()->getStoreByHandle($storeHandle);
            if ($store === null) {
                continue;
            }

            $query = Order::find()
                ->storeId($store->id)
                ->isCompleted(false)
                ->limit(null)
                ->status(null)
            ;

            $count = match ($type) {
                'active' => (int) $query->dateUpdated('>= '.$edge)->count(),
                'inactive' => (int) $query->dateUpdated('< '.$edge)->count(),
                'attempted-payment' => (int) $query->hasTransactions(true)->count(),
                default => 0,
            };

            $r[$key] = $count;
        }

        return $r;
    }

    /**
     * Counts Formie submissions per form. `$formIds` are numeric Form IDs that
     * appear in the sidebar as `data-key="form:<id>"`.
     *
     * @param array<int|string> $formIds
     *
     * @return array<int|string, int>
     */
    public function getSubmissionsCount(array $formIds = []): array
    {
        if (\count($formIds) === 0) {
            return [];
        }

        // Soft dependency on verbb/formie.
        if (!class_exists(Submission::class)) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        foreach ($formIds as $formId) {
            $count = (int) Submission::find()
                ->formId((int) $formId)
                ->limit(null)
                ->status(null)
                ->count()
            ;

            $r[$formId] = $count;
            $totalCount += $count;
        }

        $r['*'] = $totalCount;

        return $r;
    }

    /**
     * Counts Formie sent notifications per form. Same key pattern as submissions.
     *
     * @param array<int|string> $formIds
     *
     * @return array<int|string, int>
     */
    public function getSentNotificationsCount(array $formIds = []): array
    {
        if (\count($formIds) === 0) {
            return [];
        }

        if (!class_exists(SentNotification::class)) {
            return [];
        }

        $r = [];
        $totalCount = 0;

        foreach ($formIds as $formId) {
            $count = (int) SentNotification::find()
                ->formId((int) $formId)
                ->limit(null)
                ->status(null)
                ->count()
            ;

            $r[$formId] = $count;
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
