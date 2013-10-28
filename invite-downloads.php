<?php
/**
 * Plugin Name: Invite Downloads
 * Description: Lets your generate download codes for digital give-aways.
 * Version: 0.1
 * Author: indiecosmic
 * Author URI: http://indiesoft.org
 */
class InviteDownloads {

    public function __construct() {
        add_shortcode('invite_downloads', array($this, 'shortcode'));
        add_action('init', array($this, 'init'));

        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        if (is_admin()) {
            add_action('wp_ajax_invite_downloads_code_submit', array($this, 'code_submit'));
            add_action('wp_ajax_nopriv_invite_downloads_code_submit', array($this, 'code_submit'));
        }
    }

    public function init() {
        wp_register_script('invite-downloads-js', plugins_url('js/invite-downloads.js', __FILE__), array('jquery'), false, true);
        wp_register_style('invite-downloads-css', plugins_url('css/invite-downloads.css', __FILE__));
    }

    public static function invite_downloads_activation() {
        if (!current_user_can('activate_plugins'))
            return;
    }

    public static function invite_downloads_deactivation() {
        if (!current_user_can('activate_plugins'))
            return;
    }

    public function enqueue_scripts() {
        global $post;
        if (has_shortcode($post->post_content, 'invite_downloads')) {
            wp_enqueue_script('invite-downloads-js');
            wp_enqueue_style('invite-downloads-css');
        }
    }

    public function code_submit() {
        if ($_POST['download_code'] == 'fel') {
            $result = array(
                'success' => 'false',
                'error' => 'You entered an invalid code.'
            );
        } else {
            $result = array(
                'success' => 'true',
                'content' => "<div>Georgian Waters - Midsummer Air <a href='#'>Download</a></div>"
            );
        }
        header('Content-Type: application/json');
        echo json_encode($result);
        die();
    }

    public function shortcode() {
        $admin_url = admin_url('admin-ajax.php');
        $loading_url = plugins_url('images/loading.gif', __FILE__);

        return "<div id='invite_downloads_wrapper'>
                    <div class='invite_downloads_inner'>
                    <h2>Enter your download code here:</h2>
                    <div class='invite_code'>
                        <form id='invite_downloads_form' name='invite_downloads_form' method='post' action='$admin_url' enctype='multipart/form-data'>
                            <input type='hidden' name='action' value='invite_downloads_code_submit' />
                            <input type='text' name='download_code' /><input type='submit' value='Submit' /><img id='loading' src='$loading_url' alt='Loading' />
                            <div class='error-message'></div>
                        </form>
                    </div>
                    </div>
                </div>";
    }
}

if (class_exists('InviteDownloads')) {
    register_activation_hook(__FILE__, array('InviteDownloads', 'invite_downloads_activation'));
    register_deactivation_hook(__FILE__, array('InviteDownloads', 'invite_downloads_deactivation'));

    $inviteDownloads = new InviteDownloads();
}