<form class="studip_form" action="<?= $controller->url_for('configuration/store') ?>" method="post">
    <fieldset>
        <legend>
            <?= dgettext('doormanplugin', 'Grunddaten') ?>
        </legend>
        <label class="caption" for="institute">
            <?= $config ? dgettext('doormanplugin', 'Einrichtung') : dgettext('doormanplugin', 'Einrichtung auswählen') ?>
        </label>
    <?php if (!$config) { ?>
        <select name="institute">
            <?php foreach ($institutes as $id => $name) { ?>
            <option value="<?= $id ?>"><?= $name ?></option>
            <?php } ?>
        </select>
    <?php } else { ?>
        <?= htmlReady($config->institute->name) ?>
        <input type="hidden" name="institute" value="<?= $config->institute_id ?>"/>
    <?php } ?>
        <label class="caption">
            <?= dgettext('doormanplugin', 'Wie viele Tage vor Veranstaltungsbeginn sollen die Einstellungen gesetzt werden?') ?>
            <input type="number" size="4" maxlength="3" name="daysbefore" value="<?= $config ? $config->daysbefore : 7 ?>"/>
        </label>
    </fieldset>
    <fieldset>
        <legend>
            <?= dgettext('doormanplugin', 'Automatisch zu setzende Einstellungen') ?>
        </legend>
        <label>
            <input type="checkbox" name="set_admission_binding"<?= $config ? ($config->set_admission_binding ? ' checked="checked"' : '') : '' ?>>
            <?= dgettext('doormanplugin', 'Anmeldung auf verbindlich setzen?') ?>
        </label>
        <label>
            <input type="checkbox" name="disable_moving_up"<?= $config ? ($config->disable_moving_up ? ' checked="checked"' : '') : '' ?>>
            <?= dgettext('doormanplugin', 'Automatisches Nachrücken abschalten?') ?>
        </label>
        <label>
            <input type="checkbox" name="disable_waitlist"<?= $config ? ($config->disable_waitlist ? ' checked="checked"' : '') : '' ?>>
            <?= dgettext('doormanplugin', 'Warteliste deaktivieren?') ?>
        </label>
    </fieldset>
    <div class="submit_wrapper">
        <?= CSRFProtection::tokenTag(); ?>
        <?php if ($config) { ?>
        <input type="hidden" name="config_id" value="<?= $config->id ?>"/>
        <?php } ?>
        <?= \Studip\Button::create(dgettext('doormanplugin', 'Speichern'), array('data-dialog-button' => '', 'class' => 'accept')) ?>
        <?= \Studip\Button::create(dgettext('doormanplugin', 'Abbrechen'), array('data-dialog-button' => '', 'class' => 'cancel')) ?>
    </div>
</form>
