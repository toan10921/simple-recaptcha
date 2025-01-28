<?php

class ReCaptchaV2Controller
{
    private static $instance = null;

    private $client_id = '';
    private $secret_key = '';
    private $hide_badge = '';

    private function __construct()
    {
        $this->client_id = get_option('simple_recaptcha_client_key');
        $this->secret_key = get_option('simple_recaptcha_secret_key');
        $this->hide_badge = get_option('simple_recaptcha_hide_badge');

        // enqueue script
        add_action('wp_enqueue_scripts', [$this, 'enqueue_recaptcha_script']);
        // enque to admin login page
        add_action('login_enqueue_scripts', [$this, 'enqueue_recaptcha_script']);

        add_action('register_form', [$this, 'add_recaptcha_to_form_wp']);
        add_action('register_post', [$this, 'verify_recaptcha_register_form_wp'], 10, 3);

        // add action to login form admin wp

        add_action('login_form', [$this, 'add_recaptcha_to_form_wp']);
        add_filter('wp_authenticate', [$this, 'verify_recaptcha_login_form_wp'], 10, 1);

        // add action to register form woocommerce

        if ($this->is_woocommerce_exists()) {
            add_action('woocommerce_register_form', [$this, 'add_recaptcha_to_register_form_woocommerce']);
            add_action('woocommerce_register_post', [$this, 'add_recaptcha_to_register_form_woocommerce'], 10, 3);
        }

        // add action to comment form

        add_action('comment_form', [$this, 'add_recaptcha_to_form_wp']);
        add_action('pre_comment_on_post', [$this, 'verify_recaptcha_comment_form_wp']);

        add_filter('woocommerce_product_review_comment_form_args', [$this, 'add_recaptcha_to_woocomerce_product_review_comment_form']);
        add_action('wp_insert_comment', [$this, 'verify_recaptcha_review_form_wp'], 10, 1);

        add_action('wp_head', [$this, 'hide_recaptcha_badge']);
    }

    public function hide_recaptcha_badge()
    {
        if ($this->hide_badge == '1') {
            echo '<style>.grecaptcha-badge{display:none;}</style>';
        }
    }

    public function enqueue_recaptcha_script()
    {
        // wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $this->get_client_id(), [], null, false);
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, false);
        wp_enqueue_script('simple-recaptcha-script', plugin_dir_url(__DIR__) . 'assets/js/script.js', ['recaptcha'], null, true);
        // add global variable for recaptcha
        wp_localize_script('simple-recaptcha-script', 'simple_recaptcha', [
            'site_key' => $this->get_client_id()
        ]);
    }

    public function is_woocommerce_exists()
    {
        if (class_exists('WooCommerce')) {
            return true;
        }
    }

    public function get_client_id()
    {
        return $this->client_id;
    }

    public function get_secret_key()
    {
        return $this->secret_key;
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new RecaptchaV2Controller();
        }
        return self::$instance;
    }

    public function add_recaptcha_to_register_form_woocommerce()
    {
?>
        <!-- <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"> -->
        <div class="g-recaptcha" data-sitekey="your_site_key"></div>

    <?php
    }

    public function verify_recaptcha_register_form_woocommerce($username, $email, $validation_errors)
    {
        if (empty($_POST['g-recaptcha-response'])) {
            $validation_errors->add('recaptcha_error', __('Please verify that you are not a robot.', 'simple-recaptcha'));
        } else {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                'body' => array(
                    'secret' => $this->get_secret_key(),
                    'response' => $_POST['g-recaptcha-response']
                )
            ));

            $response_body = wp_remote_retrieve_body($response);
            $result = json_decode($response_body, true);

            if (!$result['success']) {
                $validation_errors->add('recaptcha_error', __('Captcha verification failed.', 'simple-recaptcha'));
            }
        }
    }

    public function add_recaptcha_to_form_wp()
    {
    ?>

        <!-- <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"> -->
        <div class="g-recaptcha" data-sitekey="your_site_key"></div>

<?php
    }

    public function verify_recaptcha_register_form_wp($login, $email, $errors)
    {
        if (empty($_POST['g-recaptcha-response'])) {
            $errors->add('recaptcha_error', __('Please verify that you are not a robot.', 'simple-recaptcha'));
        } else {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                'body' => array(
                    'secret' => $this->get_secret_key(),
                    'response' => $_POST['g-recaptcha-response']
                )
            ));

            $response_body = wp_remote_retrieve_body($response);
            $result = json_decode($response_body, true);

            if (!$result['success']) {
                $errors->add('recaptcha_error', __('Captcha verification failed.', 'simple-recaptcha'));
            }
        }
    }

    public function verify_recaptcha_login_form_wp($username)
    {

        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
                'body' => [
                    'secret'   => $this->get_secret_key(), // Replace with your reCAPTCHA secret key
                    'response' => sanitize_text_field($_POST['g-recaptcha-response']),
                    'remoteip' => $_SERVER['REMOTE_ADDR'], // Optional
                ],
            ]);

            if (is_wp_error($response)) {
                wp_die(
                    __('Unable to verify the reCAPTCHA. Please try again.', 'text-domain'),
                    __('Login Error', 'text-domain'),
                    ['back_link' => true]
                );
            }

            $response_body = json_decode(wp_remote_retrieve_body($response), true);

            if (empty($response_body['success']) || !$response_body['success']) {
                wp_die(
                    __('The reCAPTCHA verification failed. Please try again.', 'text-domain'),
                    __('Login Error', 'text-domain'),
                    ['back_link' => true]
                );
            }
        }
    }

    public function verify_recaptcha_comment_form_wp($comment_post_ID)
    {
        if (empty($_POST['g-recaptcha-response'])) {
            wp_die(
                __('Please verify that you are not a robot.', 'simple-recaptcha'),
                __('Comment Error', 'simple-recaptcha'),
                ['back_link' => true]
            );
        } else {
            $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                'body' => array(
                    'secret' => $this->get_secret_key(),
                    'response' => $_POST['g-recaptcha-response']
                )
            ));

            $response_body = wp_remote_retrieve_body($response);
            $result = json_decode($response_body, true);

            if (!$result['success']) {
                wp_die(
                    __('Captcha verification failed.', 'simple-recaptcha'),
                    __('Comment Error', 'simple-recaptcha'),
                    ['back_link' => true]
                );
            }
        }
    }

    /**
     * Function for `woocommerce_product_review_comment_form_args` filter-hook.
     * 
     * @param  $comment_form 
     *
     * @return 
     */
    public function add_recaptcha_to_woocomerce_product_review_comment_form($comment_form)
    {

        $comment_form['fields']['g-recaptcha-response'] = '<div class="g-recaptcha" data-sitekey="your_site_key"></div>';
        return $comment_form;
    }

    public function verify_recaptcha_review_form_wp($comment_id, $comment_data)
    {
        if ($comment_data['comment_type']  == 'review') {
            if (empty($_POST['g-recaptcha-response'])) {
                wp_die(
                    __('Please verify that you are not a robot.', 'simple-recaptcha'),
                    __('Comment Error', 'simple-recaptcha'),
                    ['back_link' => true]
                );
            } else {
                $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
                    'body' => array(
                        'secret' => $this->get_secret_key(),
                        'response' => $_POST['g-recaptcha-response']
                    )
                ));

                $response_body = wp_remote_retrieve_body($response);
                $result = json_decode($response_body, true);

                if (!$result['success']) {
                    wp_die(
                        __('Captcha verification failed.', 'simple-recaptcha'),
                        __('Comment Error', 'simple-recaptcha'),
                        ['back_link' => true]
                    );
                }
            }
        }
    }
}

// check if option is v3, if yes, use RecaptchaV3Controller
$recaptcha_version = get_option('simple_recaptcha_version');
if ($recaptcha_version === 'v2') {
    ReCaptchaV2Controller::getInstance();
}
