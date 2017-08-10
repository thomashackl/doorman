<h1><?= dgettext('doormanplugin', 'Konfiguration der automatischen Anmeldeeinstellungen') ?></h1>
<?php if ($configs) { ?>
<table class="default">
    <caption><?= dgettext('doormanplugin', 'Vorhandene Konfigurationen') ?></caption>
    <thead>
        <tr>
            <th><?= dgettext('doormanplugin', 'Einrichtung') ?></th>
            <th><?= dgettext('doormanplugin', 'Tage vor Beginn') ?></th>
            <th><?= dgettext('doormanplugin', 'Verbindliche Anmeldung?') ?></th>
            <th><?= dgettext('doormanplugin', 'automatisches Nachrücken?') ?></th>
            <th><?= dgettext('doormanplugin', 'Warteliste deaktivieren?') ?></th>
            <th><?= dgettext('doormanplugin', 'Aktionen') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($configs as $config) { ?>
        <tr>
            <td><?= htmlReady($config->Institute->name) ?></td>
            <td><?= $config->daysbefore ?></td>
            <td><?= $config->set_admission_binding ? dgettext('doormanplugin', 'Ja') : dgettext('doormanplugin', 'Nein') ?></td>
            <td><?= $config->disable_moving_up ? dgettext('doormanplugin', 'Ja') : dgettext('doormanplugin', 'Nein') ?></td>
            <td><?= $config->disable_waitlist ? dgettext('doormanplugin', 'Ja') : dgettext('doormanplugin', 'Nein') ?></td>
            <td>
                <a href="<?= $controller->url_for('configuration/configure', $config->id) ?>" data-dialog="size=auto" title="<?= dgettext('doormanplugin', 'Konfiguration bearbeiten') ?>">
                    <?= Icon::create('edit') ?>
                </a>
                <a href="<?= $controller->url_for('configuration/ask_delete', $config->id) ?>" title="<?= dgettext('doormanplugin', 'Konfiguration löschen') ?>">
                    <?= Icon::create('trash') ?>
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php } ?>
