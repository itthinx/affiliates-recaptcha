<?php
/**
 * affiliates-recaptcha.php
 *
 * Copyright (c) 2014 www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author itthinx
 * @package affiliates-recaptcha
 * @since affiliates-recaptcha 1.0.0
 *
 * Plugin Name: Affiliates reCAPTCHA
 * Plugin URI: https://github.com/itthinx/affiliates-recaptcha
 * Description: Affiliates registration reCAPTCHA integration. IMPORTANT : Go to Settings > Affiliates reCAPTCHA and input the Public Key and the Private Key.
 * Version: 1.0.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 */

class Affiliates_Recaptcha {

	/**
	 * Adds action and filter hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_filter( 'affiliates_captcha_get', array( __CLASS__, 'affiliates_captcha_get' ), 10, 2 );
		add_filter( 'affiliates_captcha_validate', array( __CLASS__, 'affiliates_captcha_validate' ), 10, 2 );
	}

	/**
	 * Adds a settings page.
	 */
	public static function admin_menu() {
		add_options_page(
			'Affiliates reCAPTCHA',
			'Affiliates reCAPTCHA',
			'manage_options',
			'affiliates-recaptcha',
			array( __CLASS__, 'settings' )
		);
	}

	/**
	 * Renders the settings page.
	 */
	public static function settings() {

		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Access denied.', 'affiliates-recaptcha' ) );
		}

		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'save' ) && wp_verify_nonce( $_POST['affiliates-recaptcha'], 'admin' ) ) {

			$public_key = !empty( $_POST['public_key'] ) ? trim( strip_tags( $_POST['public_key'] ) ) : '';
			$private_key = !empty( $_POST['private_key'] ) ? trim( strip_tags( $_POST['private_key'] ) ) : '';

			delete_option( 'affiliates-recaptcha-public-key' );
			delete_option( 'affiliates-recaptcha-private-key' );

			add_option( 'affiliates-recaptcha-public-key', $public_key, '', 'no' );
			add_option( 'affiliates-recaptcha-private-key', $private_key, '', 'no' );

			echo
				'<div class="updated">' .
				__( 'Settings Saved.', 'affiliates-recaptcha' ) .
				'</div>';

			if ( empty( $public_key ) ) {
				echo '<div class="error">' .
					__( 'The public key is empty, you must input a valid public key.', 'affiliates-recaptcha' ) .
					'</div>';
			}

			if ( empty( $public_key ) ) {
				echo
					'<div class="error">' .
					__( 'The private key is empty, you must input a valid private key.', 'affiliates-recaptcha' ) .
					'</div>';
			}

		}

		$public_key = get_option( 'affiliates-recaptcha-public-key', '' );
		$private_key = get_option( 'affiliates-recaptcha-private-key', '' );

		echo '<h1>';
		echo __( 'Affiliates reCAPTCHA', 'affiliates-recaptcha' );
		echo '</h1>';

		echo '<div class="settings">';
		echo '<form name="settings" method="post" action="">';
		echo '<div>';

		echo '<p>';
		echo '<label>';
		echo __( 'Public Key', 'affiliates-recaptcha' );
		echo ' ';
		printf( '<input style="display:block;width:80%%" type="text" name="public_key" value="%s" />', esc_attr( $public_key ) );
		echo '</label>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo __( 'Private Key', 'affiliates-recaptcha' );
		echo ' ';
		printf( '<input style="display:block;width:80%%" type="text" name="private_key" value="%s" />', esc_attr( $private_key ) );
		echo '</label>';
		echo '</p>';

		wp_nonce_field( 'admin', 'affiliates-recaptcha', true, true );

		echo '<br/>';

		echo '<div class="buttons">';
		echo sprintf( '<input class="create button" type="submit" name="submit" value="%s" />', __( 'Save', 'affiliates-recaptcha' ) );
		echo '<input type="hidden" name="action" value="save" />';
		echo '</div>';

		echo '</div>';
		echo '</form>';
		echo '</div>';
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
			$field_error = '<div class="error">' . __( 'Please solve the captcha to proof that you are human.', 'affiliates-recaptcha' ) . '</div>';
		}

		if ( !function_exists( 'recaptcha_get_html' ) ) {
			require_once 'includes/recaptcha/recaptchalib.php';
		}

		$field .= recaptcha_get_html( get_option( 'affiliates-recaptcha-public-key', '' ), $affiliates_recaptcha_error );
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

		global $affiliates_recaptcha_error;

		if ( !function_exists( 'recaptcha_check_answer' ) ) {
			require_once 'includes/recaptcha/recaptchalib.php';
		}

		$response = recaptcha_check_answer( get_option( 'affiliates-recaptcha-private-key' ), $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field'] );
		if ( !$response->is_valid ) {
			$affiliates_recaptcha_error = $response->error;
		} else {
			$affiliates_recaptcha_error = null;
		}

		return $result && $response->is_valid;
	}

}
add_action( 'init', array( 'Affiliates_Recaptcha', 'init' ) );
