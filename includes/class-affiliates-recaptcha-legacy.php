<?php
/**
 * class-affiliates-recaptcha-legacy.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package affiliates-recaptcha
 * @since affiliates-recaptcha 2.0.0
 */

/**
 * Affiliates reCaptcha v 1.x handler.
 */
class Affiliates_Recaptcha_Legacy {

	/**
	 * Initialize hooks that handle addition and removal of users and blogs.
	 */
	public static function init() {
	}


	/**
	 * Renders the captcha field.
	 *
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	public static function affiliates_captcha_get( $field, $value ) {

		global $affiliates_recaptcha_error;

		if ( !isset( $affiliates_recaptcha_error ) ) {
			$affiliates_recaptcha_error = null;
		}

		$field_error = '';
		if ( !empty( $affiliates_recaptcha_error ) ) {
			$field_error =
			'<div class="error">' .
			__( 'Please solve the captcha to proof that you are human.', 'affiliates-recaptcha' ) .
			'</div>';
		}

		if ( !function_exists( 'recaptcha_get_html' ) ) {
			require_once 'recaptcha/recaptchalib.php';
		}

		$field .= recaptcha_get_html(
				get_option( 'affiliates-recaptcha-public-key', '' ),
				$affiliates_recaptcha_error,
				is_ssl()
				);
		$field .= apply_filters(
				'affiliates_recaptcha_field_css',
				'<style type="text/css">' .
				'#recaptcha_area { height: 130px; overflow: hidden; }' .
				'</style>'
				);
		$field .= apply_filters( 'affiliates_recaptcha_field_error', $field_error );

		return $field;
	}

	/**
	 * Validates the captcha.
	 *
	 * @param boolean $result
	 * @param string $field_value
	 * @return boolean
	 */
	public static function affiliates_captcha_validate( $result, $field_value ) {

		global $affiliates_recaptcha_error, $affiliates_recaptcha_response;

		if ( !isset( $affiliates_recaptcha_response ) ) {

			if ( !function_exists( 'recaptcha_check_answer' ) ) {
				require_once 'recaptcha/recaptchalib.php';
			}

			$response = recaptcha_check_answer(
					get_option( 'affiliates-recaptcha-private-key' ),
					$_SERVER['REMOTE_ADDR'],
					$_POST['recaptcha_challenge_field'],
					$_POST['recaptcha_response_field']
					);
			if ( !$response->is_valid ) {
				$affiliates_recaptcha_error = $response->error;
			} else {
				$affiliates_recaptcha_error = null;
				$affiliates_recaptcha_response = $response;
			}

		} else {
			$response = $affiliates_recaptcha_response;
		}
		return $result && $response->is_valid;
	}

}
Affiliates_Recaptcha_Legacy::init();
