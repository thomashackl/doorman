<h1><?= _('Konfiguration der automatischen Anmeldeeinstellungen') ?></h1>
<?php if ($configs) { ?>
<table class="default">
    <caption><?= _('Vorhandene Konfigurationen') ?></caption>
    <thead>
        <tr>
            <th><?= _('Einrichtung') ?></th>
            <th><?= _('Tage vor Beginn') ?></th>
            <th><?= _('Verbindliche Anmeldung?') ?></th>
            <th><?= _('automatisches Nachrücken?') ?></th>
            <th><?= _('Warteliste deaktivieren?') ?></th>
            <th><?= _('Aktionen') ?></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($configs as $config) { ?>
        <tr>
            <td><?= htmlReady($config->Institute->name) ?></td>
            <td><?= $config->daysbefore ?></td>
            <td><?= $config->set_admission_binding ? _('Ja') : _('Nein') ?></td>
            <td><?= $config->disable_moving_up ? _('Ja') : _('Nein') ?></td>
            <td><?= $config->disable_waitlist ? _('Ja') : _('Nein') ?></td>
            <td>
                <a href="<?= $controller->url_for('configuration/configure', $config->id) ?>" rel="lightbox" title="<?= _('Konfiguration bearbeiten') ?>">
                    <?= Assets::img('icons/16/blue/edit.png') ?>
                </a>
                <a href="<?= $controller->url_for('configuration/ask_delete', $config->id) ?>" title="<?= _('Konfiguration löschen') ?>">
                    <?= Assets::img('icons/16/blue/trash.png') ?>
                </a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php } ?>
