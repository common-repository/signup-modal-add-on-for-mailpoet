<?php defined( 'ABSPATH' ) or die(); ?>

<?php

$privacy_policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );
$dsgvoLink = get_permalink($privacy_policy_page_id, true);

$defaultTitle = __('Newsletter', 'mailpoet-modal-dsgvo');
$defaultDesc = __('Bleibe auf dem Laufenden und erhalte die neuesten Informationen zu unseren Produkten und Aktionen per E-Mail.', 'mailpoet-modal-dsgvo');
$defaultCheckBox = sprintf(__('Die <a href="%s">Datenschutzbestimmungen</a> habe ich zur Kenntnis genommen.', 'mailpoet-modal-dsgvo'), $dsgvoLink);
$defaultHTMLText = "<h1>${defaultTitle}</h1><p>${defaultDesc}</p>[mmdsgvo-form checkBoxText='${defaultCheckBox}']";
$defaultHTMLTextJson = json_encode(array('text' => trim($defaultHTMLText)));
?>

<style type="text/css">
    .mailpoet-modal-dsgvo .tab-content {
        display: none;
    }

    .mailpoet-modal-dsgvo .tab-content.active {
        display: block;
    }

    p.submit {
        text-align: right !important;
    }
</style>

<div class="wrap mailpoet-modal-dsgvo">
    <h1><?php _e( 'Signup Modal Add-On for MailPoet', 'mailpoet-modal-dsgvo' ) ?></h1>
    <br/>
    <nav class="nav-tab-wrapper">
        <a href="#lsolcp-panel-editor" class="nav-tab nav-tab-active"><?php _e('Modal Dialog', 'mailpoet-modal-dsgvo'); ?></a>
        <a href="#lsolcp-panel-local" class="nav-tab"><?php _e('Local Storage', 'mailpoet-modal-dsgvo'); ?></a>
        <a href="#lsolcp-panel-common" class="nav-tab"><?php _e('Einstellungen', 'mailpoet-modal-dsgvo'); ?></a>
        <a href="#lsolcp-panel-tracking" class="nav-tab"><?php _e('Tracking', 'mailpoet-modal-dsgvo'); ?></a>
        <a href="#lsolcp-panel-help" class="nav-tab"><?php _e('Hilfe', 'mailpoet-modal-dsgvo'); ?></a>
    </nav>

    <form method="post" action="options-general.php?page=<?php echo MMGDSGVO_SSLUG; ?>">
        <?php wp_nonce_field( MMGDSGVO_SSLUG.'_settings_form' ); ?>

        <input type="hidden" name="<?php echo MMGDSGVO_SSLUG; ?>_settings_form" value="1">

        <section id="lsolcp-panel-help" class="tab-content">
            <div class="card" style="max-width: 800px;">
                <p>
                    <b><?php _e( 'Hast du Fragen zu MailPoet Modal?', 'mailpoet-modal-dsgvo' ); ?></b><br/><br>
                    <?php _e('Dann', 'mailpoet-modal-dsgvo'); ?>  <a href="https://www.lamp-solutions.de/kontakt/" target="_blank"><?php _e('hinterlasse uns eine Nachricht', 'mailpoet-modal-dsgvo'); ?></a>.<br/>
                    <?php _e('Wir freuen uns über deine Anfrage und werden sie zeitnah beantworten.', 'mailpoet-modal-dsgvo'); ?><br><br>
                    <?php _e('Das Plugin ist ein OpenSource Projekt der', 'mailpoet-modal-dsgvo'); ?> <a href="https://www.lamp-solutions.de/" target="_blank">
                        <img style="width: 16px;" src="data:image/png;base64,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" alt="LAMP solutions GmbH">LAMP solutions GmbH</a> <?php _e('aus Nürnberg', 'mailpoet-modal-dsgvo'); ?>.
                </p>

            </div>
        </section>

        <section id="lsolcp-panel-local" class="tab-content">
            <div class="card" style="max-width: 800px;">


                <table class="form-table" role="presentation">
                    <tbody>

                    <tr>
                        <th scope="row">
                            <?php _e( 'Lokale Daten löschen', 'mailpoet-modal-dsgvo' ); ?>
                        </th>
                        <td>
                            <p class="description">
                                <?php _e("Im Local Storage wird gespeichert, ob der Webseitenbesucher sich bereits in den Newsletter eingetragen hat.", 'mailpoet-modal-dsgvo'); ?>
                                <br><br>
                                <?php _e("Zur Löschung der Daten kann folgender Shortcode verwendet werden:<br><code>[mmdsgvo-clear-data text='' classes='' alert='']</code>", 'mailpoet-modal-dsgvo'); ?>
                                <br><br>
                                <b>Parameter</b><br>
                                <?php _e("Text des Hyperlinks<br> <code>text='Browserdaten löschen'</code>", 'mailpoet-modal-dsgvo'); ?>
                                <br><br>
                                <?php _e("CSS Klassen<br><code>classes=''</code>", 'mailpoet-modal-dsgvo'); ?>
                                <br><br>
                                <?php _e("Erfolgsmeldung bei erfolgreicher Löschung der Daten.<br> <code>alert='Lokale Browserdaten erfolgreich gelöscht'</code>", 'mailpoet-modal-dsgvo'); ?>
                                <br><br>
                                <?php _e("<b>Mögliche Nutzungsmöglichkeiten:</b> <br>Datenschutzerklärung", 'mailpoet-modal-dsgvo'); ?>
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <p class="submit">
                    <?php echo do_shortcode('[mmdsgvo-clear-data classes="button"]'); ?>
                </p>
            </div>
        </section>

        <section id="lsolcp-panel-tracking" class="tab-content">
            <div class="card" style="max-width: 800px;">

                <?php if(!is_plugin_active('cookie-panel/cookie-panel.php')) { ?>
                <div class="error-tab" style="background: #fff; border: 1px solid #ccd0d4; border-left-width: 4px; box-shadow: 0 1px 1px rgba(0,0,0,.04); margin: 5px 0 15px; padding: 1px 12px; border-left-color: #dc3232;">
                    <p>
                        <?php
                        $mp_link = '<a href="https://de.wordpress.org/plugins/cookie-panel/" target="_blank">Cookie Panel</a>';
                        printf(
                            __('%s Tracking benötigt das %s plugin, bitte aktiviere zuerst %s um die Tracking Funktionalität zu benutzen.', 'mailpoet-modal-dsgvo'),
                            MMDSGVO_NAME,
                            $mp_link,
                            $mp_link,
                            MMDSGVO_NAME
                        );
                        ?>
                    </p>
                </div>
                <?php } ?>

                <table class="form-table" role="presentation">
                    <tbody>

                    <tr>
                        <th scope="row"><?php _e( 'Tracking aktivieren', 'mailpoet-modal-dsgvo' ); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e( 'Tracking aktivieren', 'mailpoet-modal-dsgvo' ); ?></span>
                                </legend>

                                <input name="<?php echo MMGDSGVO_SSLUG; ?>_tracking_active" type="checkbox" id="'<?php echo MMGDSGVO_SSLUG; ?>'_tracking_active" value="1" <?php checked(get_option(MMGDSGVO_SSLUG.'_tracking_active')) ?>>
                                <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_active"><?php _e('Aktiviert die Tracking Integration mit Cookie Panel', 'mailpoet-modal-dsgvo'); ?></label>
                                <label class="description" for=""><?php _e('Falls im <a href="https://de.wordpress.org/plugins/cookie-panel/" target="_blank">Cookie Panel</a> Matomo oder Google Analytics konfiguriert ist werden automatisch die jeweiligen Events übermittelt.', 'mailpoet-modal-dsgvo'); ?></label>
                                <label class="description" for=""><?php _e('Die Events lauten:', 'mailpoet-modal-dsgvo'); ?></label>
                                <ul style="list-style: inherit; margin-left: 20px;">
                                    <li>mailpoet_modal_view</li>
                                    <li>mailpoet_modal_conversion</li>
                                </ul>
                                <label class="description" for=""><?php printf(__('Eine Beispieleinrichtung der Daten unter Matomo findest Du <a target="_blank" href="%s/img/matomo_ziele.png">hier</a>'), MMDSGVO_URL); ?>.</label>
                            </fieldset>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Änderungen speichern', 'mailpoet-modal-dsgvo'); ?>">
                </p>
            </div>
        </section>

        <section id="lsolcp-panel-editor" class="tab-content active">
            <div class="card" style="max-width: 800px;">
                <table class="form-table" role="presentation">
                    <tbody>

                    <tr>
                        <th scope="row"><?php _e( 'Signup Modal aktivieren', 'mailpoet-modal-dsgvo' ); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php _e( 'Signup Modal aktivieren', 'mailpoet-modal-dsgvo' ); ?></span>
                                </legend>

                                <input name="<?php echo MMGDSGVO_SSLUG; ?>_active" type="checkbox" id="'<?php echo MMGDSGVO_SSLUG; ?>'_active" value="1" <?php checked(get_option(MMGDSGVO_SSLUG.'_active')) ?>>
                                <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_active"><?php _e('Aktiviert den Modal-Dialog für Webseitenbesucher', 'mailpoet-modal-dsgvo'); ?></label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <?php _e( 'Modal Editor', 'mailpoet-modal-dsgvo' ); ?>
                        </th>
                        <td>
                            <?php
                            wp_editor( stripslashes(get_option(MMGDSGVO_SSLUG.'-editor', $defaultHTMLText)), MMGDSGVO_SSLUG.'-editor', $settings = array('editor_height' => '250px') );
                            ?>
                            <script type="text/javascript">
                                var loadMMDSGVODefaults = function() {
                                    if(!tinyMCE.activeEditor)jQuery('.wp-editor-wrap .switch-tmce').trigger('click');
                                    var content = JSON.parse("<?php echo addslashes($defaultHTMLTextJson); ?>");
                                    tinyMCE.activeEditor.setContent(content.text);
                                }
                            </script>

                            <p class="description">
                                <?php _e("Zur Darstellung des Formulars <b>muss</b> folgender Shortcode verwendet werden:<br><code>[mmdsgvo-form submitText='Anmelden' checkBoxText='']</code><br><br>Falls der <b>checkBoxText</b> gesetzt ist, wird im Formular eine Checkbox angezeigt.<br>Mit <b>submitText</b> wird der Text für das Absende Button definiert", 'mailpoet-modal-dsgvo'); ?>
                            </p>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="button" id="mmdsgvo-reset-form" class="button" onclick="loadMMDSGVODefaults();" value="<?php _e('Beispieltext laden', 'mailpoet-modal-dsgvo'); ?>">
                    <a target="_blank" href="<?php esc_attr_e(get_home_url().'?mmdsgvoPreviewMode=1') ?>" id="mmdsgvo-preview-form" class="button"><?php _e('Vorschau anzeigen', 'mailpoet-modal-dsgvo'); ?></a>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Änderungen speichern', 'mailpoet-modal-dsgvo'); ?>">
                </p>

            </div>
        </section>

        <section id="lsolcp-panel-common" class="tab-content">
            <div class="card" style="max-width: 800px;">
                <table class="form-table" role="presentation">
                    <tbody>

                        <tr>
                            <th scope="row"><?php _e( 'Formular', 'mailpoet-modal-dsgvo' ); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Horizontale Darstellung', 'mailpoet-modal-dsgvo' ); ?></span>
                                    </legend>

                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_horizontal" type="checkbox" id="'<?php echo MMGDSGVO_SSLUG; ?>'_horizontal" value="1" <?php checked(get_option(MMGDSGVO_SSLUG.'_horizontal')) ?>>
                                    <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_horizontal"><?php _e('Das Formular wird horizontal dargestellt', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Vorname Feld', 'mailpoet-modal-dsgvo' ); ?></span>
                                    </legend>

                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_first_name" type="checkbox" id="'<?php echo MMGDSGVO_SSLUG; ?>'_first_name" value="1" <?php checked(get_option(MMGDSGVO_SSLUG.'_first_name')) ?>>
                                    <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_first_name"><?php _e('Zeige Vorname Feld im Formular', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Nachname Feld', 'mailpoet-modal-dsgvo' ); ?></span>
                                    </legend>

                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_last_name" type="checkbox" id="'<?php echo MMGDSGVO_SSLUG; ?>'_last_name" value="1" <?php checked(get_option(MMGDSGVO_SSLUG.'_last_name')) ?>>
                                    <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_last_name"><?php _e('Zeige Nachname Feld im Formular', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e( 'Dankes-Seite', 'cookie-panel' ) ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Dankes-Seite', 'cookie-panel' ) ?></span>
                                    </legend>

                                    <?php
                                    wp_dropdown_pages(
                                        array(
                                            'name'              => MMGDSGVO_SSLUG.'_success_page_id',
                                            'show_option_none'  => __( '&mdash; Select &mdash;' ),
                                            'option_none_value' => '0',
                                            'selected'          => get_option(MMGDSGVO_SSLUG.'_success_page_id'),
                                            'post_status'       => array( 'draft', 'publish' ),
                                        )
                                    );
                                    ?>
                                    <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_success_page_id"><?php _e('Der Benutzer wird nach erfolgreicher Eintragung an diese Seite umgeleitet.', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e('MailPoet Listen', 'mailpoet-modal-dsgvo'); ?></th>
                            <td>
                                <label class="description"><?php _e('Neue Anmeldungen werden in folgende Listen eingetragen:', 'mailpoet-modal-dsgvo'); ?></label>
                                <?php foreach($mailPoetLists as $list) { ?>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e('MailPoet Listen', 'mailpoet-modal-dsgvo'); ?></span>
                                    </legend>

                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_lists[<?php esc_attr_e($list['id']); ?>]" type="checkbox" id="<?php echo MMGDSGVO_SSLUG; ?>_lists-<?php esc_attr_e($list['id']); ?>" value="<?php esc_attr_e($list['id']); ?>" <?php checked(in_array($list['id'], get_option(MMGDSGVO_SSLUG.'_lists', []))) ?>>
                                    <label class="description" for="<?php echo MMGDSGVO_SSLUG; ?>_lists-<?php esc_attr_e($list['id']); ?>"><?php esc_html_e($list['name']); ?></label>
                                </fieldset>
                                <?php } ?>

                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e( 'Anzeige nach', 'mailpoet-modal-dsgvo' ); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Anzeige nach', 'mailpoet-modal-dsgvo' ) ?></span>
                                    </legend>

                                    <label for=<?php echo MMGDSGVO_SSLUG; ?>-delay"></label>
                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_delay" type="number" min="0" id="<?php echo MMGDSGVO_SSLUG; ?>-delay" value="<?php echo esc_attr( get_option(MMGDSGVO_SSLUG.'_delay', 15) ); ?>" class="text"> <?php _e('Sekunden', 'mailpoet-modal-dsgvo'); ?>
                                    <label class="description"><?php _e('Anzahl der Sekunden, bis das Signup Modular angezeigt wird.', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e( 'Erneute Anzeige in', 'mailpoet-modal-dsgvo' ); ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Erneute Anzeige in', 'mailpoet-modal-dsgvo' ) ?></span>
                                    </legend>

                                    <label for=<?php echo MMGDSGVO_SSLUG; ?>-delay"></label>
                                    <input name="<?php echo MMGDSGVO_SSLUG; ?>_restart" type="number" min="0" id="<?php echo MMGDSGVO_SSLUG; ?>-delay" value="<?php echo esc_attr( get_option(MMGDSGVO_SSLUG.'_restart', 15) ); ?>" class="text"> <?php _e('Minuten', 'mailpoet-modal-dsgvo'); ?>
                                    <label class="description"><?php _e('Anzahl der Minuten, bis das Signup Modal wieder angezeigt wird (0 deaktiviert dieses Feature).', 'mailpoet-modal-dsgvo'); ?></label>
                                    <label class="description"><?php _e('Eine erneute Anzeige des Modals wird bei erfolgreicher Eintragung oder im Fehlerfall deaktiviert.', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><?php _e( 'Darstellung verhindern auf folgenden URLs', 'cookie-panel' ) ?></th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">
                                        <span><?php _e( 'Darstellung verhindern auf folgenden URLs', 'cookie-panel' ) ?></span>
                                    </legend>

                                    <label for="<?php echo MMGDSGVO_SSLUG.'-blacklist_regex'; ?>"></label>
                                    <textarea name="<?php echo MMGDSGVO_SSLUG.'_blacklist_regex'; ?>" cols="50" rows="5" id="<?php echo MMGDSGVO_SSLUG.'-blacklist_regex'; ?>"><?php echo stripslashes(esc_textarea( get_option(MMGDSGVO_SSLUG.'_blacklist_regex', '')) ); ?></textarea>
                                    <label class="description"><?php _e('Eine Liste von URLs auf denen das Signup Modal nicht dargestellt werden soll. Unterstützt das Wildcard Zeichen wie /post/* am Ende einer URL. Pro URL eine Zeile.', 'mailpoet-modal-dsgvo'); ?></label>
                                </fieldset>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <?php  submit_button(); ?>
            </div>
        </section>

    </form>

    <script>
        jQuery('.nav-tab').click(function(e) {
            jQuery(this).addClass('nav-tab-active').siblings().removeClass('nav-tab-active');
            jQuery(jQuery(this).attr('href')).addClass('active').siblings().removeClass('active');
            return false;
        });
    </script>
</div>