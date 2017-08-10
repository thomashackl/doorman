<?php
/**
 * DoormanCronjob.php
 *
 * Creates and executes cron jobs for automagically configuring
 * admission settings.
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

/**
 * Cron job for processing configurations.
 */
class DoormanCronjob extends CronJob {

    public static function getName() {
        return 'Automatische Einstellungen der Zugangsberechtigungen';
    }

    public static function getDescription() {
        return 'Setzt automatisch vor Beginn einer Veranstaltung Einstellungen für Zugangsberechtigungen';
    }

    public static function getParameters() {
        return array();
    }

    public function setUp() {

    }

    /**
     * Check for courses settings that need to be changes.
     */
    public function execute($last_result, $parameters = array()) {
        StudipAutoloader::addAutoloadPath(realpath(dirname(__FILE__).'/models'));
        Log::set('doorman', '/var/log/studip/doorman.log');
        $data = DoormanConfig::getAll();
        foreach ($data as $c) {
            $config = DoormanConfig::find($c);
            Log::info_doorman('At institute '.$config->Institute->name);
            $semester = Semester::findCurrent();
            $db = DBManager::get();
            $query = "SELECT `Seminar_id`
                FROM `seminare`
                WHERE `Institut_id`=:instid
                    AND (`start_time`+`duration_time`>=:semester
                        OR (`start_time`>=:semester AND `duration_time`=-1))";
            if ($config->set_admission_binding) {
                $query .= " AND (`admission_binding`=0 OR `admission_binding` IS NULL)";
            }
            if ($config->disable_moving_up) {
                $query .= " AND (`admission_disable_waitlist_move`=0 OR `admission_disable_waitlist_move` IS NULL)";
            }
            if ($config->disable_waitlist) {
                $query .= " AND (`admission_disable_waitlist`=0 OR `admission_disable_waitlist` IS NULL)";
            }
            $query .= " ORDER BY `Seminar_id` ASC";
            $courses = $db->fetchFirst($query, array('instid' => $config->institute_id, 'semester' => $semester->beginn));
            foreach ($courses as $c) {
                $start = veranstaltung_beginn($c, true);
                if ($start && $start <= time()+($config->daysbefore*24*60*60)) {
                    Log::info_doorman('Checking course '.$c);
                    Log::info_doorman("\tCourse starts on ".date('d.m.y H:i', $start).".");
                    $course = Course::find($c);
                    if ($config->set_admission_binding) {
                        $course->admission_binding = 1;
                        Log::info_doorman("\tEnabled binding admission.");
                    }
                    if ($config->disable_moving_up) {
                        $course->admission_disable_waitlist_move = 1;
                        Log::info_doorman("\tDisabled automatic moving up from waiting list.");
                    }
                    if ($config->disable_waitlist) {
                        $course->admission_disable_waitlist = 1;
                        Log::info_doorman("\tDisabled waiting list.");
                    }
                    $course->store();
                }
            }
            Log::info_doorman("--------------------------------------------------------------------------------");
        }
    }

    public function tearDown() {

    }
}
