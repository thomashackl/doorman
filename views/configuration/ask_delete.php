<?= $GLOBALS['template_factory']->render('shared/question',
    array('question' => sprintf(_('Wollen Sie wirklich die Konfiguration für die Einrichtung "%s" löschen?'), $delete_config->Institute->name),
          'approvalLink' => $controller->url_for('configuration/delete', $delete_config->id).'?ticket='.get_ticket(),
          'disapprovalLink' => $controller->url_for('configuration'))) ?>
