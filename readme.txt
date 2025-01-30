=== Simple reCAPTCHA ===
Contributors: toanngo92
Donate link: https://trienkhaiweb.com
Tags: simple reCAPTCHA, google reCAPTCHA, simple recaptcha
Requires at least: 4.7
Tested up to: 6.7.1
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Quickly integrate Google reCAPTCHA into WordPress and WooCommerce forms: register, login, comment, and review. Simple and effective protection.

== Description ==

This plugin is ideal for anyone looking to enhance the security of their WordPress or WooCommerce site by integrating Google reCAPTCHA. It provides a simple and effective way to protect your forms from spam and abuse. Whether you need to secure your registration, login, comment, or review forms, this plugin offers a seamless integration process. It is designed to be user-friendly, requiring minimal configuration while offering robust protection. Perfect for site administrators who want to ensure a smooth user experience while keeping their site secure.

== Installation ==

Install Simpe reCAPTCHA like you would install any other WordPress plugin.

Dashboard Method:

1. Login to your WordPress admin and go to Plugins -> Add New
2. Type "Simpe reCAPTCHA" in the search bar and select this plugin
3. Click "Install", and then "Activate Plugin"


Upload Method:

1. Unzip the plugin and upload the "simple-recaptcha" folder to your 'wp-content/plugins' directory
2. Activate the plugin through the Plugins menu in WordPress

= Using Simple reCAPTCHA =

1. Once activated, Simple reCAPTCHA will add a page under the "Settings" menu in your WordPress admin.
2. Go to https://www.google.com/recaptcha/admin to register your domain and choose the reCAPTCHA version. We support both v2 and v3.
3. Go to the settings page and check the "Enable Recaptcha Feature" checkbox to enable plugin features.
4. Fill in your `client_key` and `secret_key` that you received from the Google Dashboard.
5. Choose the correct version (v2 or v3).
6. Click "Save" to apply all your settings.

== Frequently Asked Questions ==

== Want to contribute? ==

Feel free to open an issue or submit a pull request on [GitHub](https://github.com/toan10921/simple-recaptcha).

= Is my host supported? =

Yes! This plugin should be compatible with any host.

= Can I damage my site with this plugin? =

No, this plugin will not damage your site. However, it requires you to provide a valid `client_key` and `secret_key` to ensure that the login and register pages work properly. Failure to provide these keys can affect the functionality of the login and register features.

== Screenshots ==

1. Simple reCAPTCHA page added to the "Settings" menu

== Changelog ==

= 1.0 =
* Initial release