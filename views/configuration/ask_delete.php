<?= $GLOBALS['template_factory']->render('shared/question',
    array('question' => sprintf(_('Wollen Sie wirklich die Konfiguration f�r die Einrichtung "%s" l�schen?'), $delete_config->Institute->name),
          'approvalLink' => $controller->url_for('configuration/delete', $delete_config->id).'?ticket='.get_ticket(),
          'disapprovalLink' => $controller->url_for('configuration'))) ?>
