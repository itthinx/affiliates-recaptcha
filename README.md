affiliates-recaptcha
====================

Affiliates registration reCAPTCHA integration

Integrates <a href="https://www.google.com/recaptcha/">reCAPTCHA</a> with the <a href="https://www.itthinx.com/plugins/affiliates/">Affiliates</a> registration form.

Works with <a href="https://www.itthinx.com/plugins/affiliates/">Affiliates</a>, <a href="https://www.itthinx.com/shop/affiliates-pro/">Affiliates Pro</a> and <a href="https://www.itthinx.com/shop/affiliates-enterprise/">Affiliates Enterprise</a>.

Installation & Setup
--------------------

1. Install Affiliates reCAPTCHA from your WordPress dashboard or download the plugin ZIP from https://github.com/itthinx/affiliates-recaptcha/ and extract it to your plugins folder.
2. Upload and activate the plugin.
3. Get the public and private reCAPTCHA keys for your site from https://www.google.com/recaptcha/admin/list
4. Go to Settings > Affiliates reCAPTCHA and input the public and private key.

Filters
-------

- affiliates_recaptcha_field_css : allows to modify the CSS that is output to limit the #recaptcha_area container
- affiliates_recaptcha_field_error : allows to modify the output when the CAPTCHA has not been responded to correctly
