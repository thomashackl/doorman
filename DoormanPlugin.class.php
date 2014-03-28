<?php
/**
 * DoormanPlugin.class.php
 * 
 * Plugin for automatic configuration of admission related settings X days
 * before course starts.
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 */

require 'bootstrap.php';

class DoormanPlugin extends StudIPPlugin implements SystemPlugin {

    /**
     * Name for cron job.
     */
    const CRON = "DoormanCronjob.php";

    /**
     * Create a new instance initializing navigation and needed scripts.
     */
    public function __construct() {
        if ($GLOBALS['perm']->have_perm('root')) {
            parent::__construct();
            // Localization
            bindtextdomain('doormanplugin', realpath(dirname(__FILE__).'/locale'));
            $navigation = new Navigation($this->getDisplayName(), PluginEngine::getURL($this, array(), 'configuration'));
            Navigation::addItem('/admin/config/doorman', $navigation);
        }
    }

    /**
     * Plugin name to show in navigation.
     */
    public function getDisplayName() {
        return dgettext('doormanplugin', 'Automatische Anmeldeeinstellungen');
    }

    public function perform($unconsumed_path) {
        $this->setupAutoload();
        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            'configuration'
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }

    private function setupAutoload() {
        StudipAutoloader::addAutoloadPath(realpath(dirname(__FILE__).'/models'));
    }

    public static function onEnable($pluginId) {
        parent::onEnable($pluginId);
        $taskId = CronjobScheduler::registerTask(self::getCronName(), true);
        CronjobScheduler::schedulePeriodic($taskId, 25, 0);
    }

    public static function onDisable($pluginId) {
        $taskId = CronjobTask::findByFilename(self::getCronName());
        CronjobScheduler::unregisterTask($taskId[0]->task_id);
        parent::onDisable($pluginId);
    }

    private static function getCronName() {
        return "public/plugins_packages/intelec/DoormanPlugin/".self::CRON;
        $plugin = PluginEngine::getPlugin(__CLASS__);
        $path = $plugin->getPluginPath();
        return dirname($path)."/".self::CRON;
    }

}
