<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

add_action( 'wp_ajax_dappier_get_agent_data', 'dappier_get_agent_data' );
/**
 * Get the agent data via ajax.
 *
 * @since TBD
 *
 * @return void
 */
function dappier_get_agent_data() {
	$api_key    = $_POST['api_key'];
	$aimodel_id = $_POST['aimodel_id'];

	// Set up the API url and body.
	$url = 'https://api.dappier.com/v1/integrations/agent/' . $aimodel_id;

	// Set up the request arguments.
	$args = [
		'headers' => [
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $api_key,
		],
	];

	// Make the request.
	$body     = [];
	$response = wp_remote_get( $url, $args );
	$code     = wp_remote_retrieve_response_code( $response );

	// Check for errors.
	if ( 200 !== $code ) {
		// Send error.
		wp_send_json_error( $response );
	}

	// Get the body.
	$body = wp_remote_retrieve_body( $response );
	$body = json_decode( $body, true );

	// Return success.
	wp_send_json_success( $body );
}