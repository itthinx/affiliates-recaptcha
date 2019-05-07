<?php
/**
 * affiliates-recaptcha.php
 *
 * Copyright (c) 2014 - 2017 www.itthinx.com
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
 * Description: Affiliates registration reCAPTCHA integration. IMPORTANT : Go to Settings > Affiliates reCAPTCHA and input the Site Key and the Secret Key.
 * Version: 2.1.0
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin reCAPTCHA handler for the API v2.
 * Renders admin and handles captcha.
 */
class Affiliates_Recaptcha {

	/**
	 * Adds action and filter hooks.
	 */
	public static function init() {
		add_action( 'affiliates_admin_menu', array( __CLASS__, 'affiliates_admin_menu' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( __CLASS__, 'plugin_action_links' ) );
		if ( !apply_filters( 'affiliates_recaptcha_legacy', false ) ) {
			add_filter( 'affiliates_captcha_get', array( __CLASS__, 'affiliates_captcha_get' ), 10, 2 );
			add_filter( 'affiliates_captcha_validate', array( __CLASS__, 'affiliates_captcha_validate' ), 10, 2 );
		} else {
			require_once 'includes/class-affiliates-recaptcha-legacy.php';
			add_filter( 'affiliates_captcha_get', array( 'Affiliates_Recaptcha_Legacy', 'affiliates_captcha_get' ), 10, 2 );
			add_filter( 'affiliates_captcha_validate', array( 'Affiliates_Recaptcha_Legacy', 'affiliates_captcha_validate' ), 10, 2 );
		}
	}

	/**
	 * Adds a submenu item to the Affiliates menu for integration options.
	 */
	public static function affiliates_admin_menu() {
		$page = add_submenu_page(
			'affiliates-admin',
			__( 'Affiliates reCAPTCHA', 'affiliates-recaptcha' ),
			__( 'reCAPTCHA', 'affiliates-recaptcha' ),
			AFFILIATES_ADMINISTER_OPTIONS,
			'affiliates-admin-recaptcha',
			array( __CLASS__, 'settings' )
		);
		$pages[] = $page;
		add_action( 'admin_print_styles-' . $page, 'affiliates_admin_print_styles' );
		add_action( 'admin_print_scripts-' . $page, 'affiliates_admin_print_scripts' );
	}

	/**
	 * Adds a link to our settings on the plugin entry.
	 *
	 * @param array $links
	 * @return array
	 */
	public static function plugin_action_links( $links ) {
		if ( current_user_can( 'manage_options' ) ) {
			$_links = array();
			$_links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=affiliates-admin-recaptcha' ) ), esc_html( __( 'Settings', 'affiliates-recaptcha' ) ) );
			$links = $_links + $links;
		}
		return $links;
	}

	/**
	 * Renders the settings page.
	 */
	public static function settings() {

		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Access denied.', 'affiliates-recaptcha' ) );
		}

		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'save' ) && wp_verify_nonce( $_POST['affiliates-recaptcha'], 'admin' ) ) {

			$public_key  = !empty( $_POST['public_key'] ) ? trim( strip_tags( $_POST['public_key'] ) ) : '';
			$private_key = !empty( $_POST['private_key'] ) ? trim( strip_tags( $_POST['private_key'] ) ) : '';

			delete_option( 'affiliates-recaptcha-public-key' );
			delete_option( 'affiliates-recaptcha-private-key' );

			add_option( 'affiliates-recaptcha-public-key', $public_key, '', 'no' );
			add_option( 'affiliates-recaptcha-private-key', $private_key, '', 'no' );

			echo
				'<div class="updated">' .
				esc_html__( 'Settings Saved.', 'affiliates-recaptcha' ) .
				'</div>';

			if ( empty( $public_key ) ) {
				echo '<div class="error">' .
					esc_html__( 'The public key is empty, you must input a valid public key.', 'affiliates-recaptcha' ) .
					'</div>';
			}

			if ( empty( $public_key ) ) {
				echo
					'<div class="error">' .
					esc_html__( 'The private key is empty, you must input a valid private key.', 'affiliates-recaptcha' ) .
					'</div>';
			}

		}

		$public_key = get_option( 'affiliates-recaptcha-public-key', '' );
		$private_key = get_option( 'affiliates-recaptcha-private-key', '' );

		echo '<h1>';
		echo esc_html__( 'Affiliates reCAPTCHA', 'affiliates-recaptcha' );
		echo '</h1>';

		echo '<div class="settings">';
		echo '<form name="settings" method="post" action="">';
		echo '<div>';

		echo '<p>';
		echo '<label>';
		echo esc_html__( 'Site Key', 'affiliates-recaptcha' );
		echo ' ';
		printf( '<input style="display:block;width:80%%" type="text" name="public_key" value="%s" />', esc_attr( $public_key ) );
		echo '</label>';
		echo '</p>';

		echo '<p>';
		echo '<label>';
		echo esc_html__( 'Secret Key', 'affiliates-recaptcha' );
		echo ' ';
		printf( '<input style="display:block;width:80%%" type="text" name="private_key" value="%s" />', esc_attr( $private_key ) );
		echo '</label>';
		echo '</p>';

		wp_nonce_field( 'admin', 'affiliates-recaptcha', true, true );

		echo '<br/>';

		echo '<div class="buttons">';
		echo sprintf( '<input class="create button" type="submit" name="submit" value="%s" />', esc_html__( 'Save', 'affiliates-recaptcha' ) );
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
			$field_error =
			'<div class="error">' .
			__( 'Please solve the captcha.', 'affiliates-recaptcha' ) .
			'</div>';
		}

		wp_register_script( 'affiliates-recaptcha-api', 'https://www.google.com/recaptcha/api.js', array( ), '2.0.0' );
		wp_enqueue_script( 'affiliates-recaptcha-api' );

		$field .= '<div class="g-recaptcha" data-sitekey="' . get_option( 'affiliates-recaptcha-public-key', '' ) . '"></div>';

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

		if ( isset( $_POST['g-recaptcha-response'] ) ) {
			$captcha = $_POST['g-recaptcha-response'];
		}
		if ( !$captcha ) {
			$affiliates_recaptcha_error = true;
			return false;
		}
		$response = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . get_option( 'affiliates-recaptcha-private-key', '' ) . '&response=' . $captcha . '&remoteip=' . $_SERVER['REMOTE_ADDR'] );
		$response = json_decode( $response['body'], true );

		if ( $response['success'] !== true ) {
			return false;
		}
		return $result;
	}
}
add_action( 'init', array( 'Affiliates_Recaptcha', 'init' ) );
