<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class SimpleRecaptchaAdminOptions
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        // only add script in options page
        add_action('admin_enqueue_scripts', function ($hook_suffix) {
            if ($hook_suffix === 'settings_page_simple-recaptcha') {
                $this->add_scripts_option_page();
            }
        });
        // add filter to add settings link on plugin page
        add_filter('plugin_action_links_simple-recaptcha/simple-recaptcha.php', [$this, '_add_settings_link']);

    }

    public function _add_settings_link($links) {
        $settings_link = '<a href="options-general.php?page=simple-recaptcha">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
   

    public function add_admin_menu()
    {
        add_options_page(
            __('Simple Recaptcha Options', 'simple-recaptcha'),
            __('Simple Recaptcha', 'simple-recaptcha'),
            'manage_options',
            'simple-recaptcha',
            array($this, 'options_page')
        );
    }

    public function register_settings()
    {
        register_setting('simple_recaptcha_options', 'simple_recaptcha_enable_feature', array(
            'type' => 'string',
            'default' => '1',
            'sanitize_callback' => 'sanitize_text_field'
        ));
        register_setting('simple_recaptcha_options', 'simple_recaptcha_secret_key');
        register_setting('simple_recaptcha_options', 'simple_recaptcha_client_key');
        register_setting('simple_recaptcha_options', 'simple_recaptcha_version'); // Register new setting
        register_setting('simple_recaptcha_options', 'simple_recaptcha_hide_badge'); // Register new setting
    }

    public function add_scripts_option_page()
    {
        wp_enqueue_style('simple-recaptcha-admin-style', plugin_dir_url(__DIR__) . 'assets/css/admin-style.css');
        wp_enqueue_script('simple-recaptcha-admin-script', plugin_dir_url(__DIR__) . 'assets/js/admin-script.js', ['jquery'], null, true);
        wp_localize_script('simple-recaptcha-admin-script', 'simple_recaptcha', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'action' => 'handleAjaxBanner',
            
        ]);
    }

    public function options_page()
    {
?>
        <div class="options-outer">
            <div class="wrap">

                <h1><?php _e('Simple Recaptcha Options', 'simple-recaptcha'); ?></h1>
                <form method="post" action="options.php">
                    <?php
                    settings_fields('simple_recaptcha_options');
                    do_settings_sections('simple_recaptcha_options');
                    ?>
                    <p class="desc"><?php echo __('Go to <a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a> to register your client key and secret key for your domain', 'simple-recaptcha') ?></p>
                    <div class="options-wrap">
                        <div class="table-wrap">
                            <table class="form-table">
                                <tr valign="top">
                                    <th scope="row"><?php _e('Enable Recaptcha Feature', 'simple-recaptcha'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="simple_recaptcha_enable_feature" value="1" <?php echo (esc_attr(get_option('simple_recaptcha_enable_feature')) == '1') ? 'checked' : '' ?> />
                                            <?php _e('Enable Recaptcha Feature', 'simple-recaptcha'); ?>
                                        </label>
                                    </td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Client Key', 'simple-recaptcha'); ?></th>
                                    <td><input type="text" name="simple_recaptcha_client_key" value="<?php echo esc_attr(get_option('simple_recaptcha_client_key')); ?>" /></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row"><?php _e('Secret Key', 'simple-recaptcha'); ?></th>
                                    <td><input type="text" name="simple_recaptcha_secret_key" value="<?php echo esc_attr(get_option('simple_recaptcha_secret_key')); ?>" /></td>
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
                                <tr valign="top">
                                    <th scope="row"><?php _e('Hide recaptcha badge', 'simple-recaptcha'); ?></th>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="simple_recaptcha_hide_badge" value="1" <?php checked(get_option('simple_recaptcha_hide_badge'), 1); ?> />
                                            <?php _e('Hide recaptcha badge', 'simple-recaptcha'); ?>
                                        </label>
                                    </td>
                                </tr>
                            </table>
                            <?php submit_button(); ?>
                        </div>
                        <div class="banner-wrap">
                            <div class="plugin-advertising-banner">

                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
<?php
    }
}

new SimpleRecaptchaAdminOptions();
