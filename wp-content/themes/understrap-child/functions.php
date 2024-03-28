<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	wp_localize_script( 'child-understrap-scripts', 'ajax_form_object', array(
		'url'   => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ajax-form-nonce' ),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );


/**
 * Get term for realty post type
 */
function get_realty_term( ) {
	$terms = get_the_terms( get_the_ID(), 'realty_type' );
	if ( ! $terms )
		return null;
	return $term = array_shift( $terms );
}

add_action( 'wp_enqueue_scripts', 'ajax_form_scripts' );
function ajax_form_scripts() {
	wp_enqueue_script( 'jquery-form' );
}

add_action( 'wp_ajax_ajax_form_action', 'ajax_action_callback' );
add_action( 'wp_ajax_nopriv_ajax_form_action', 'ajax_action_callback' );

function ajax_action_callback() {

	$errors = [];

	if ( !wp_verify_nonce( $_POST['nonce'], 'ajax-form-nonce' ) ) {
		wp_die( 'Данные отправлены с некорректного адреса' );
	}

	if ( empty( $_POST['realty_name'] ) || !isset( $_POST['realty_name'] ) ) {
		$errors['name'] = 'Пожалуйста, введите название объекта.';
	} else {
		$realty_name = sanitize_text_field( $_POST['realty_name'] );
	}

	if ( empty( $_POST['realty_area'] ) || !isset( $_POST['realty_area'] ) ) {
		$errors['area'] = 'Пожалуйста, введите площадь объекта';
	} else {
		$realty_area = sanitize_text_field( $_POST['realty_area'] );
	}

	if ( empty( $_POST['realty_cost'] ) || !isset( $_POST['realty_cost'] ) ) {
		$errors['cost'] = 'Пожалуйста, введите цену объекта';
	} else {
		$realty_cost = sanitize_text_field( $_POST['realty_cost'] );
	}

	if ( empty( $_POST['realty_address'] ) || !isset( $_POST['realty_address'] ) ) {
		$errors['address'] = 'Пожалуйста, введите адрес объекта';
	} else {
		$realty_address = sanitize_text_field( $_POST['realty_address'] );
	}

	if ( empty( $_POST['realty_living_area'] ) || !isset( $_POST['realty_living_area'] ) ) {
		$errors['living_area'] = 'Пожалуйста, введите жилую площадь объекта';
	} else {
		$realty_living_area = sanitize_text_field( $_POST['realty_living_area'] );
	}

	if ( empty( $_POST['realty_floor'] ) || !isset( $_POST['realty_floor'] ) ) {
		$errors['floor'] = 'Пожалуйста, введите этаж объекта';
	} else {
		$realty_floor = sanitize_text_field( $_POST['realty_floor'] );
	}

	if ( $errors ) {

		wp_send_json_error( $errors );

	} else {
		$post_data = [
			'post_title' => $realty_name,
			'post_status' => 'pending',
			'post_author' => 1,
			'post_type' => 'realty',
			'post_parent' => $_POST[ 'object_city' ],
		];
		$post_id = wp_insert_post( $post_data );
		if ( $post_id ) {
			update_field( 'field_66040a2a9fc68', $realty_area, $post_id );
			update_field( 'field_66040a7c9fc69', $realty_cost, $post_id );
			update_field( 'field_66040a919fc6a', $realty_address, $post_id );
			update_field( 'field_66040aa79fc6b', $realty_living_area, $post_id );
			update_field( 'field_66040ae39fc6c', $realty_floor, $post_id );
			wp_set_object_terms( $post_id, [ (int) $_POST['cat'] ], 'realty_type' );
		} else {
			wp_send_json_error( ['Error inserting post', 'Не удалось добавить новый объект недвижимости'] );
		}
		// Отправляем сообщение об успешной отправке
		$message_success = 'Объект недвижимости успешно добавлен.';
		wp_send_json_success( $message_success );
	}

	// Убиваем процесс ajax
	wp_die();

}