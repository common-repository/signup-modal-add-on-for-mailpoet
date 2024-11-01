<?php

/*
Plugin Name: Signup Modal Add-On for MailPoet
Description: DSGVO-konformes MailPoet Signup Modal
Version: 1.0.8
Author: LAMP solutions GmbH
Author URI: https://www.lamp-solutions.de
License: GPL3
Text Domain: mailpoet-modal-dsgvo
Domain Path: /languages
*/


defined( 'ABSPATH' ) or die();
define('MMDSGVO_WPDIR', ABSPATH);
define('MMDSGVO_NAME', 'Signup Modal Add-On for MailPoet');
define('MMDSGVO_DIR', plugin_dir_path(__FILE__));
define('MMDSGVO_URL', plugin_dir_url(__FILE__));
define('MMDSGVO_SLUG', plugin_basename(__FILE__));
define('MMGDSGVO_SSLUG', 'mmdsgvo');
define('MMDSGVO_TEXTDOMAIN_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/');
define('MMDSGVO_PLUG_FILE', __FILE__);


class MMDSGVO {
    public function __construct() {
        add_action('admin_init', array($this, 'check_mailpoet'));

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if(is_plugin_active('mailpoet/mailpoet.php')) {
            add_action( 'wp_ajax_nopriv_'.MMGDSGVO_SSLUG.'_register', array($this, 'register') );
            add_action( 'wp_ajax_'.MMGDSGVO_SSLUG.'_register', array($this, 'register') );
            add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_shortcode( MMGDSGVO_SSLUG.'-form', array($this, 'shortcode') );
            add_shortcode( MMGDSGVO_SSLUG.'-clear-data', array($this, 'opt_out_shortcode_link') );

            if(is_admin()) {
                add_action( 'admin_init', array($this, 'handleFormSubmit') );
                add_action( 'admin_init', array($this, 'register_setting') );
                add_action( 'admin_menu', array($this, 'add_options_page') );
                add_filter( 'plugin_action_links_'.MMGDSGVO_SSLUG, array($this, 'add_settings_link') );
                add_filter( 'plugin_action_links_'.MMDSGVO_SLUG, array($this, 'add_settings_link') );
            }
        }
    }

    function conditions_logic() {
        $urls = stripslashes(get_option(MMGDSGVO_SSLUG.'_blacklist_regex', ''));
        foreach(explode("\n", $urls) as $url) {
            $url = trim($url);

            if(strpos($url, '*') === strlen($url)-1 ) {
                $url = trim($url, '*');

                if(strpos($_SERVER['REQUEST_URI'], $url) === 0) {
                    return false;
                }
            } else {
                if(strcmp($_SERVER['REQUEST_URI'], $url) == 0) {
                    return false;
                }
            }
        }

        return true;
    }

    function add_settings_link( $links ) {
        $links[] = '<a href="' . admin_url( 'options-general.php?page='.MMGDSGVO_SSLUG ) . '">' . __('Settings') . '</a>';
        return $links;
    }

    public function register() {
        $subscriber_data = array();
        $subscriber_data['email'] = sanitize_email($_REQUEST['email']);
        if(isset($_REQUEST['first_name'])) $subscriber_data['first_name'] = sanitize_text_field($_REQUEST['first_name']);
        if(isset($_REQUEST['last_name'])) $subscriber_data['last_name'] = sanitize_text_field($_REQUEST['last_name']);
        $mailpoet_selected_list_ids = get_option(MMGDSGVO_SSLUG.'_lists', []);
        $response = array();

        try {
            $subscriber = \MailPoet\API\API::MP('v1')->addSubscriber($subscriber_data, $mailpoet_selected_list_ids);
            $response['subscribed'] = true;
        } catch (\Exception $e) {
            $response['subscribed'] = false;
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        die();
    }

    public function check_mailpoet() {
        if(!is_plugin_active('mailpoet/mailpoet.php')){
            add_action('admin_notices', function(){
                ?>
                <div class="error">
                    <p>
                        <?php
                        $mp_link = '<a href="https://wordpress.org/plugins/mailpoet/" target="_blank">MailPoet</a>';
                        printf(
                            __('%s plugin benötigt das %s plugin, bitte aktiviere zuerst %s um %s zu benutzen.', 'mailpoet-modal-dsgvo'),
                            MMDSGVO_NAME,
                            $mp_link,
                            $mp_link,
                            MMDSGVO_NAME
                        );
                        ?>
                    </p>
                </div>
                <?php
            });

            return;
        }
    }

    public function opt_out_shortcode_link( $atts ) {
        $a = shortcode_atts( array(
            'text' => __('Lokale Browserdaten löschen', 'mailpoet-modal-dsgvo'),
            'alert' => __('Lokale Browserdaten erfolgreich gelöscht', 'mailpoet-modal-dsgvo'),
            'classes' => ''
        ), $atts );

        $text = esc_attr($a['text']);
        $classes = esc_attr($a['classes']);
        $alert = esc_attr($a['alert']);

        return '<a href="" class="'.$classes.'" onclick=\'localStorage.removeItem("mmdsgvo_restart"); localStorage.removeItem("mmdsgvo_start"); localStorage.removeItem("mmdsgvo_opt_out"); alert("'.$alert.'"); return false;\'>'.$text.'</a>';
    }

    public function shortcode( $atts ) {
        $a = shortcode_atts( array(
            'submittext' => __('Anmelden', 'mailpoet-modal-dsgvo'),
            'checkboxtext' => ''
        ), $atts );

        $buttonText = $a['submittext'];
        $checkBoxText = $a['checkboxtext'];

        $action = MMGDSGVO_SSLUG.'_register';
        $classes = '';
        $markup = '';
        $input = '';
        $checkbox = '';

        if(!empty($checkBoxText)) {
            $checkbox = '<div class="mmdsgvo-checkbox-wrapper"><input type="checkbox" id="dsgvo-checkbox" class="mmdsgvo-checkbox" required> <label for="dsgvo-checkbox">'.$checkBoxText.'</label></div>';
        }

        $horizontalView = get_option(MMGDSGVO_SSLUG.'_horizontal', false);
        if($horizontalView) {
            $classes .= ' horizontal ';
        }

        if(get_option(MMGDSGVO_SSLUG.'_first_name', false)) {
            $input .= '<input type="text" name="first_name" class="input" autocomplete="given-name" required placeholder="'.__('Vorname', 'mailpoet-modal-dsgvo').'" >';
        }

        if(get_option(MMGDSGVO_SSLUG.'_last_name', false) && get_option(MMGDSGVO_SSLUG.'_first_name', false)) {
            $input .= '<input type="text" name="last_name" class="input" autocomplete="family-name" required placeholder="'.__('Nachname', 'mailpoet-modal-dsgvo').'">';
        } elseif(get_option(MMGDSGVO_SSLUG.'_last_name', false)) {
            $input .= '<input type="text" name="last_name" class="input" autocomplete="family-name" required placeholder="'.__('Name', 'mailpoet-modal-dsgvo').'">'; // Keep Family Name
        }


        $emailLabel = __('E-Mail Adresse', 'mailpoet-modal-dsgvo');
        if(get_option(MMGDSGVO_SSLUG.'_first_name', false) || get_option(MMGDSGVO_SSLUG.'_last_name', false)) {

            $markup = <<<EOF
                <div class="mmdsgvo-form-wrapper">
                <form class="mmdsgvo-form" autocomplete="on">
                        <input type="hidden" name="action" value="${action}">
                        <div class="input-wrapper ${classes}">
                            ${input}
                            <input type="email" required name="email" class="input" autocomplete="email" placeholder="${emailLabel}" >
                        </div>
                        ${checkbox}
                        <input class="submit" style="width: 100%;" type="submit" value="${buttonText}">
                        <div class="error-message"></div>
                </form>
                </div>
EOF;
        } else {
            $markup = <<<EOF
                <div class="mmdsgvo-form-wrapper">
                    <form class="mmdsgvo-form" autocomplete="on">
                            <input type="hidden" name="action" value="${action}">
                            <div class="input-wrapper ${classes}">
                                <input type="email" required name="email" class="input" autocomplete="email" placeholder="${emailLabel}" >
                                ${checkbox}
                                <input class="submit" type="submit" value="${buttonText}">
                            </div>
                            <div class="error-message"></div>
                    </form>
                    
                </div>
EOF;
        }

        return $markup;
    }

    public function register_setting() {
        add_option( 'mmdgsvo_option_name', MMDSGVO_NAME);
        register_setting( 'mmdgsvo_options_group', 'mmdgsvo_option_name', 'mmdgsvo_callback' );
    }

    public function add_options_page() {
        add_options_page(__('Signup Modal', 'mailpoet-modal-dsgvo'), __('Signup Modal', 'mailpoet-modal-dsgvo'), 'manage_options', MMGDSGVO_SSLUG, array($this, 'options_page'));
    }

    public function options_page() {
        $mailPoetLists = \MailPoet\API\API::MP('v1')->getLists();
        include MMDSGVO_DIR.'/settings-page.php';
    }


    public function handleFormSubmit() {
        if(!isset($_POST[MMGDSGVO_SSLUG.'_settings_form'])) return;
        if(!check_admin_referer( MMGDSGVO_SSLUG.'_settings_form' )) return;

        $form_fields_checkbox = array(
            MMGDSGVO_SSLUG.'_active',
            MMGDSGVO_SSLUG.'_horizontal',
            MMGDSGVO_SSLUG.'_first_name',
            MMGDSGVO_SSLUG.'_last_name',
            MMGDSGVO_SSLUG.'_tracking_active'
        );

        $form_fields_int = array(
            MMGDSGVO_SSLUG.'_success_page_id',
            MMGDSGVO_SSLUG.'_delay',
            MMGDSGVO_SSLUG.'_restart',
        );

        $form_fields_text = array(
            //MMGDSGVO_SSLUG.'mmdsgvo-editor',
        );

        $form_fields_textarea = array(
            MMGDSGVO_SSLUG.'-editor',
            MMGDSGVO_SSLUG.'_blacklist_regex'
        );

        $form_fields_int_array = array(
            MMGDSGVO_SSLUG.'_lists',
        );

        foreach($form_fields_text as $form_name) {
            $form_value = sanitize_text_field($_POST[$form_name]);
            update_option($form_name, $form_value);
        }

        foreach($form_fields_textarea as $form_name) {
            $form_value = wp_kses_post($_POST[$form_name]);
            update_option($form_name, $form_value);
        }

        foreach($form_fields_int as $form_name) {
            $form_value = intval($_POST[$form_name]);
            update_option($form_name, $form_value);
        }

        foreach($form_fields_int_array as $form_name) {
            if(isset($_POST[$form_name])) {
                $form_value = array_map('intval', $_POST[$form_name]);
                update_option($form_name, $form_value);
            } else {
                update_option($form_name, []);
            }
        }

        foreach($form_fields_checkbox as $form_name) {
            update_option($form_name, isset($_POST[$form_name]) && !empty($_POST[$form_name]));
        }

    }

    public function enqueue_scripts() {

        wp_enqueue_style('mmdsgvo-modal', MMDSGVO_URL.'css/mailpoet-modal.css');
        wp_enqueue_script('mmdsgvo-modal', MMDSGVO_URL.'js/mailpoet-modal.js', array('jquery'));

        $view = ''; $register = '';

        if (get_option(MMGDSGVO_SSLUG . '_tracking_active')) { // tracking is activated
            $view = '';
            $register = '';

            if (!empty(get_option('lsolcp_matomo_url')) && !empty(get_option('lsolcp_matomo_id'))) { // matomo is configured
                $view = " if(typeof _paq != 'undefined') { _paq.push(['trackEvent', 'MailPoetModal', 'Newsletter', 'mailpoet_modal_view']); } ".$view;
                $register =" if(typeof _paq != 'undefined') { _paq.push(['trackEvent', 'MailPoetModal', 'Newsletter', 'mailpoet_modal_conversion']);  } ".$register;
            }

            if(!empty(get_option("lsolcp_analytics_id"))) { // analytics is configured
                $view = " if(typeof ga != 'undefined' && typeof ga == 'function') { 
                    ga('set', 'nonInteraction', true); ga('send', 'event', { eventCategory: 'Newsletter', eventAction: 'mailpoet_modal_view', eventLabel: 'Signup Modal View', eventValue: 1}); 
                } else if(typeof gtag != 'undefined' && typeof gtag == 'function') {
                    gtag('event', 'mailpoet_modal_view', {
                      'event_category' : 'Newsletter',
                      'event_label' : 'Signup Modal View',
                      'non_interaction': true
                    });
                } ".$view;
                $register=" if(typeof ga != 'undefined' && typeof ga == 'function') {
                    ga('set', 'nonInteraction', false); ga('send', 'event', { eventCategory: 'Newsletter', eventAction: 'mailpoet_modal_conversion', eventLabel: 'Signup Modal Conversion', eventValue: 1});
                } else if(typeof gtag != 'undefined' && typeof gtag == 'function') {
                    gtag('event', 'mailpoet_modal_conversion', {
                      'event_category' : 'Newsletter',
                      'event_label' : 'Signup Modal Conversion',
                      'non_interaction': false
                    });
                } ".$register;
            }
        }

        wp_localize_script( 'mmdsgvo-modal', MMGDSGVO_SSLUG, array(
                'active' => (int)get_option(MMGDSGVO_SSLUG.'_active', false) && $this->conditions_logic(),
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'action' => MMGDSGVO_SSLUG.'_register',
                'mode' => 'delay',
                'delay' => intval(get_option(MMGDSGVO_SSLUG.'_delay', 15)),
                'restart' => intval(get_option(MMGDSGVO_SSLUG.'_restart', 15)),
                'success' => get_permalink(get_option(MMGDSGVO_SSLUG.'_success_page_id')),
                'error' => __('Die Anmeldung war nicht erfolgreich.<br/>Entweder ist Ihre Adresse bereits eingetragen oder die E-Mail Adresse ist ungültig.', 'mailpoet-modal-dsgvo'),
                'view' => $view,
                'register' => $register,
                'markup' => $this->get_markup(),
            )
        );

    }
    public function get_markup() {
        $content = do_shortcode(stripslashes(get_option(MMGDSGVO_SSLUG.'-editor', '')));;
        return <<<EOF
        
<div id="mmdsgvo" class="mmdsgvo">
    <!--<div class="modal-overlay" id="modal-overlay"></div>-->
    
    <div class="modal" id="modal">
        <div class="modal-inner-content">
            <button class="close-button">x</button>
            <div class="modal-guts">
                <div>
                    ${content}
                </div>
            </div>
        </div>
    </div>
</div>
EOF;


    }
}

$mmdsgvo = new MMDSGVO();