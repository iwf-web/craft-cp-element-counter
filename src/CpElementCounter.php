<?php

namespace cpelementcounter;

use cpelementcounter\assetbundles\CpElementCounterAssetBundle;
use cpelementcounter\services\CountService;
use Craft;
use craft\base\Plugin;
use craft\events\TemplateEvent;
use craft\services\Plugins;
use craft\web\View;
use yii\base\Event;
use yii\base\InvalidConfigException;

/**
 * Control Panel Element Counter plugin
 *
 * @method static CpElementCounter getInstance()
 * @author IWF <s.friedrich@iwf.ch>
 * @author André Elvan (thanks!)
 * @copyright IWF
 * @license MIT
 */
class CpElementCounter extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * CpElementCounter::$plugin
     *
     * @var CpElementCounter
     */
    public static CpElementCounter $plugin;

    public string $schemaVersion = '1.0.0';

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Craft::setAlias('@cpelementcounter', __DIR__);

        // Register services
        $this->setComponents([
            'count' => CountService::class,
        ]);

        if (Craft::$app->getRequest()->getIsCpRequest()) {
            Event::on(
                Plugins::class,
                Plugins::EVENT_AFTER_LOAD_PLUGINS,
                function (
                ) {
                    $this->addTemplateEvents();
                }
            );
        }
    }


    private function addTemplateEvents(): void
    {
        // Register CP Asset bundle
        Event::on(View::class,
            View::EVENT_BEFORE_RENDER_TEMPLATE,
            function (
                TemplateEvent $event
            ) {
                try {
                    Craft::$app->getView()->registerAssetBundle(CpElementCounterAssetBundle::class);
                } catch (InvalidConfigException $e) {
                    Craft::error(
                        'Error registering AssetBundle - '.$e->getMessage(),
                        __METHOD__
                    );
                }
            }
        );
    }

}
