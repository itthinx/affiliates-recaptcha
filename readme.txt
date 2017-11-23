=== Affiliates reCAPTCHA ===
Contributors: itthinx
Donate link: http://www.itthinx.com/shop/
Tags: affiliate, affiliates, captcha, recaptcha, affiliate marketing
Requires at least: 4.0.0
Tested up to: 4.9
Requires PHP: 5.5.0
Stable tag: 2.0.0
License: GPLv3

Affiliates, Affiliates Pro and Affiliates Enterprise registration reCAPTCHA integration.

== Description ==

Integrates [Google reCAPTCHA](http://www.google.com/recaptcha/) with the affiliate registration of [Affiliates](https://wordpress.org/plugins/affiliates/), [Affiliates Pro](https://www.itthinx.com/shop/affiliates-pro/) and [Affiliates Enterprise](https://www.itthinx.com/shop/affiliates-enterprise/).

When you use the `[affiliates_registration]` shortcode and this extension has been configured with the correct Site Key and Secret Key for the Google reCAPTCHA, the reCAPTCHA will be displayed and verified on the affiliate registration form.

= Requirements =
[Affiliates](https://wordpress.org/plugins/affiliates/), [Affiliates Pro](https://www.itthinx.com/shop/affiliates-pro/) or [Affiliates Enterprise](https://www.itthinx.com/shop/affiliates-enterprise/).

= Setup =
1. Install and activate the plugin.
2. Get the Site and Secret reCAPTCHA keys for your site from http://www.google.com/recaptcha/
3. Go to *Affiliates > reCAPTCHA* and input the *Site Key* and the *Secret Key*.

== Changelog ==

= 2.0.0 =
* WordPress 4.9 tested.
* Updated for the latest Google reCaptcha API.
* Added the affiliates_recaptcha_legacy filter.
* Moved the settings to the right place under the Affiliates menu.

= 1.0.2 =
* Added a link to the settings on the plugin entry.
* Fixed an issue when HTTPS is used and loading mixed active content would be blocked by browsers.

= 1.0.1 =
* Updated the wording for keys.
* Updated the link to reCAPTCHA.
* Fixed an issue with multiple form shortcode renderings.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 2.0.0 =
This version has been tested with WordPress 4.9 and uses the latest Google reCAPTCHA API.
