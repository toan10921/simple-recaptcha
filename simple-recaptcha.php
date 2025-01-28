<?php

/**
 * Plugin Name: Simple reCAPTCHA
 * Description: Simple and lightweight reCAPTCHA for WordPress login, registration, and comment form , wocoomerce form.
 * Plugin URI: https://trienkhaiweb.com
 * Author: toango92
 * Author URI: https://trienkhaiweb.com
 * Version: 1.0.0
 * Text Domain: simple-recaptcha
 * Domain Path: /languages/
 * License: GPLv2 or later
 */

require_once plugin_dir_path(__FILE__) . 'controllers/admin-options.php';
require_once plugin_dir_path(__FILE__) . 'controllers/recaptcha-v3-controller.php';
require_once plugin_dir_path(__FILE__) . 'controllers/recaptcha-v2-controller.php';