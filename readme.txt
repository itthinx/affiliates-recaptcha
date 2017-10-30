=== Affiliates reCAPTCHA ===
Contributors: itthinx
Donate link: http://www.itthinx.com/shop/affiliates-pro/
Tags: affiliate, affiliates, captcha, recaptcha
Requires at least: 4.0.0
Tested up to: 4.8.2
Stable tag: 2.0.0
License: GPLv3

Affiliates, Affiliates Pro and Affiliates Enterprise registration reCAPTCHA integration.

== Description ==

Integrates [reCAPTCHA](http://www.google.com/recaptcha/) with the [Affiliates](http://www.itthinx.com/plugins/affiliates/) registration form.

Works with [Affiliates](http://www.itthinx.com/plugins/affiliates/), [Affiliates Pro](http://www.itthinx.com/shop/affiliates-pro/) and [Affiliates Enterprise](http://www.itthinx.com/shop/affiliates-enterprise/).

= Setup =

1. Install and activate the plugin.
2. Get the Site and Secret reCAPTCHA keys for your site from http://www.google.com/recaptcha/
3. Go to Settings > Affiliates reCAPTCHA and input the *Site Key* and the *Secret Key*.

Filters
-------

- affiliates_recaptcha_field_css : allows to modify the CSS that is output to limit the #recaptcha_area container
- affiliates_recaptcha_field_error : allows to modify the output when the CAPTCHA has not been responded to correctly

== Changelog ==

= 2.0.0 =
* Added support with Google reCaptcha v2.

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

= 1.0.2 =
This version fixes an issue which would result in a failure to display the captcha when HTTPS is used,
it also adds a convenient settings link to the plugin entry.
