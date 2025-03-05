<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * Get the allowed post types for the endpoint and content ingestion.
 *
 * @access private
 *
 * @since 0.7.0
 *
 * @return array
 */
function dappier_get_allowed_post_types() {
	$post_types = (array) dappier_get_option( 'post_types' );
	$post_types = apply_filters( 'dappier_allowed_post_types', $post_types );
	$post_types = array_map( 'sanitize_key', $post_types );

	return $post_types;
}

/**
 * Check if the plugin is configured and has required settings.
 *
 * @since 0.7.0
 *
 * @return bool
 */
function dappier_is_configured() {
	static $cache = null;

	if ( ! is_null( $cache ) ) {
		return $cache;
	}

	$api_key        = dappier_get_option( 'api_key' );
	$aimodel_id     = dappier_get_option( 'aimodel_id' );
	$datamodel_id   = dappier_get_option( 'datamodel_id' );
	$external_dm_id = dappier_get_option( 'external_dm_id' );
	$widget_id      = dappier_get_option( 'widget_id' );
	$cache          = $api_key && $aimodel_id && $datamodel_id && $external_dm_id && $widget_id;

	return $cache;
}

/**
 * Enqueue the askai script.
 *
 * @since 0.7.0
 *
 * @return void
 */
function dappier_enqueue_loader() {
	// First time flag.
	static $first = true;

	// Bail if not the first.
	if ( ! $first ) {
		return;
	}

	// Add the script.
	wp_enqueue_script( 'dappier-loader' );

	// Not first anymore.
	$first = false;
}

/**
 * Get an option from the dappier options.
 *
 * @since 0.1.0
 *
 * @param string $key The option key.
 *
 * @return string
 */
function dappier_get_option( $key ) {
	$options = get_option( 'dappier', [] );

	return isset( $options[ $key ] ) ? $options[ $key ] : null;
}

/**
 * Update an option in the dappier options.
 *
 * @since 0.1.0
 *
 * @param string $key   The option key.
 * @param string $value The option value.
 *
 * @return bool
 */
function dappier_update_option( $key, $value ) {
	$options         = get_option( 'dappier', [] );
	$options[ $key ] = $value;

	return update_option( 'dappier', $options );
}

/**
 * Delete an option from the dappier options.
 *
 * @since 0.1.0
 *
 * @param string $key The option key.
 *
 * @return bool
 */
function dappier_delete_option( $key ) {
	$options = get_option( 'dappier', [] );

	if ( isset( $options[ $key ] ) ) {
		unset( $options[ $key ] );

		return update_option( 'dappier', $options );
	}

	return false;
}
