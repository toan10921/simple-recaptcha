<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SimpleRecaptchaAdminOptions {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_options_page(
            __('Simple Recaptcha Options', 'simple-recaptcha'),
            __('Simple Recaptcha', 'simple-recaptcha'),
            'manage_options',
            'simple-recaptcha',
            array($this, 'options_page')
        );
    }

    public function register_settings() {
        register_setting('simple_recaptcha_options', 'simple_recaptcha_secret_key');
        register_setting('simple_recaptcha_options', 'simple_recaptcha_client_key');
        register_setting('simple_recaptcha_options', 'simple_recaptcha_version'); // Register new setting
    }

    public function options_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Simple Recaptcha Options', 'simple-recaptcha'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('simple_recaptcha_options');
                do_settings_sections('simple_recaptcha_options');
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Secret Key', 'simple-recaptcha'); ?></th>
                        <td><input type="text" name="simple_recaptcha_secret_key" value="<?php echo esc_attr(get_option('simple_recaptcha_secret_key')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Client Key', 'simple-recaptcha'); ?></th>
                        <td><input type="text" name="simple_recaptcha_client_key" value="<?php echo esc_attr(get_option('simple_recaptcha_client_key')); ?>" /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Recaptcha Version', 'simple-recaptcha'); ?></th>
                        <td>
                            <select name="simple_recaptcha_version">
                                <option value="v3" <?php selected(get_option('simple_recaptcha_version'), 'v3'); ?>><?php _e('v3', 'simple-recaptcha'); ?></option>
                                <option value="v2" <?php selected(get_option('simple_recaptcha_version'), 'v2'); ?>><?php _e('v2', 'simple-recaptcha'); ?></option>
                            </select>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

new SimpleRecaptchaAdminOptions();