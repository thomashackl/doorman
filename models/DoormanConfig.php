<?php
/**
 * DoormanConfig.php - model class for Doorman plugin
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Thomas Hackl <thomas.hackl@uni-passau.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 *
 * @property string config_id database column
 * @property string id alias column for institute_id
 * @property string institute_id database column
 * @property bool set_admission_binding database column
 * @property bool disable_moving_up database column
 * @property bool disable_waitlist database column
 * @property int daysbefore database column
 * @property Institute institute belongs_to Institute
 */

class DoormanConfig extends SimpleORMap {

    protected static function configure($config = array()) {
        $config['db_table'] = 'doormanplugin';

        $config['belongs_to']['institute'] = array(
            'class_name' => 'Institute',
            'foreign_key' => 'institute_id'
        );

        parent::configure($config);
    }

    public static function getAll() {
        return DBManager::get()->fetchFirst(
            "SELECT dp.`config_id`
            FROM `doormanplugin` dp
                INNER JOIN `Institute` i ON (dp.`institute_id`=i.`Institut_id`)
            ORDER BY i.`Name` ASC");
    }

}
