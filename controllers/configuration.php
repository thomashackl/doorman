<?php
/**
 * configuration.php
 *
 * Configuration functionality for Doorman: which institutes shall have
 * automatic course settings?
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

class ConfigurationController extends AuthenticatedController {

    public function before_filter(&$action, &$args) {
        $GLOBALS['perm']->check('root');
        $this->current_action = $action;
        $this->validate_args($args);
        $this->flash = Trails_Flash::instance();
        if (Request::isXhr()) {
            $this->set_layout(null);
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
        $configs = DoormanConfig::getAll();
        foreach ($configs as $config) {
            $this->configs[] = DoormanConfig::find($config);
        }
    }

    public function index_action() {
        $helpbar = Helpbar::Get();
        $helpbar->addPlainText('', dgettext('doormanplugin', 'Stellen Sie hier '.
            'für Veranstaltungen ausgewählter Einrichtungen ein, ob automatisch '.
            'vor Veranstaltungsbeginn bestimmte Einstellungen an den '.
            'Zugangsberechtigungen vorgenommen werden sollen.'));

        $sidebar = Sidebar::Get();
        $actionsWidget = new ActionsWidget();
        $actionsWidget->addLink(dgettext('doormanplugin',
            'Neue Konfiguration für Einrichtung anlegen'),
            $this->url_for('configuration/configure'),
            Icon::create('add', 'clickable'))->asDialog('size=auto');
        $sidebar->addWidget($actionsWidget);
    }

    public function configure_action($id='') {
        if ($id) {
            $this->config = DoormanConfig::find($id);
        } else {
            $insts = Institute::getInstitutes();
            $this->institutes = array();
            foreach ($insts as $i) {
                if ($this->configs) {
                    $configured =  array_map(function($e) { return $e->Institute->id; }, $this->configs);
                } else {
                    $configured = array();
                }
                if (!in_array($i['Institut_id'], $configured)) {
                    if ($i['is_fak']) {
                        $this->institutes[$i['Institut_id']] = '<b>'.htmlReady($i['Name']).'</b>';
                    } else {
                        $this->institutes[$i['Institut_id']] = '&nbsp;&nbsp;'.htmlReady($i['Name']);
                    }
                }
            }
        }
        PageLayout::setTitle($id ?
            dgettext('doormanplugin', 'Konfiguration bearbeiten') :
            dgettext('doormanplugin', 'Konfiguration hinzufügen'));
    }

    public function store_action() {
        CSRFProtection::verifyUnsafeRequest();
        if (Request::option('config_id')) {
            $config = DoormanConfig::find(Request::option('config_id'));
        } else {
            $config = new DoormanConfig();
            $config->institute_id = Request::option('institute');
        }
        $config->daysbefore = Request::int('daysbefore');
        $config->set_admission_binding = Request::get('set_admission_binding') ? 1 : 0;
        $config->disable_moving_up = Request::get('disable_moving_up') ? 1 : 0;
        $config->disable_waitlist = Request::get('disable_waitlist') ? 1 : 0;
        $config->store();
        $this->redirect($this->url_for('configuration'));
    }

    public function ask_delete_action($id) {
        $this->delete_config = DoormanConfig::find($id);
    }

    public function delete_action($id) {
        $this->check_ticket();
        $c = DoormanConfig::find($id);
        $n = $c->institute->name;
        if ($c->delete()) {
            PageLayout::postMessage(MessageBox::success(sprintf(dgettext('doormanplugin', 'Die Konfiguration für %s wurde gelöscht.'), $n)));
        } else {
            PageLayout::postMessage(MessageBox::error(sprintf(dgettext('doormanplugin', 'Die Konfiguration für %s konnte nicht gelöscht werden.'), $n)));
        }
        $this->redirect($this->url_for('configuration'));
    }

    /**
     * Validate ticket (passed via request environment).
     * This method always checks Request::quoted('ticket').
     *
     * @throws InvalidArgumentException  if ticket is not valid
     */
    private function check_ticket() {
        if (!check_ticket(Request::option('ticket'))) {
            throw new InvalidArgumentException(dgettext('doormanplugin', 'Das Ticket für diese Aktion ist ungültig.'));
        }
    }
}
