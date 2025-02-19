<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

add_action( 'init', 'dappier_askai_block_init' );
/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 *
 * @since TBD
 *
 * @return void
 */
function dappier_askai_block_init() {
	register_block_type( __DIR__ . '/block.json', [
		'render_callback' => 'dappier_render_askai_block',
	] );
}

/**
 * Render the Dappier AskAI block.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Returns the block content.
 */
function dappier_render_askai_block( $attributes, $content, $block ) {
	try {
		// Bail if not configured.
		if ( ! function_exists( 'dappier_is_configured' ) || ! dappier_is_configured() ) {
			if ( is_admin() ) {
				return '<p>' . esc_html__( 'Dappier is not configured. Please go to the Dappier settings page to configure it.', 'dappier' ) . '</p>';
			}

			return '';
		}

		// Filter out empty string attributes.
		$attributes = array_filter( $attributes, function( $value ) {
			return '' !== $value;
		} );

		// Check if we're in the block editor.
		$editor = defined('REST_REQUEST') && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// Force the initial search query to the home URL when in the block editor.
		if ( $editor && ! ( isset( $attributes['initialSearchQuery'] ) && $attributes['initialSearchQuery'] ) ) {
			$attributes['initialSearchQuery'] = home_url();
		}

		// Force transparent when no color is set.
		if ( ! isset( $attributes['mainBackgroundColor'] ) || empty( $attributes['mainBackgroundColor'] ) ) {
			$attributes['mainBackgroundColor'] = 'transparent';
		}

		// Force these defaults when using the block.
		$attributes['mainLogoUrl'] = '';
		$attributes['enableTitle'] = 'false';

		// Instantiate the AskAI class with block attributes.
		$askai = new Dappier_AskAi( $attributes );
		$askai->set_location( 'block' );

		// Get the rendered HTML.
		$html = $askai->render();

		// Ensure we're returning a string.
		if ( empty( $html ) ) {
			if ( $editor ) {
				return '<p>' . esc_html__( 'The AskAI widget is not rendering. Please check your configuration.', 'dappier' ) . '</p>';
			}

			return '';
		}

		return $html;

	} catch ( Exception $e ) {
		if ( $editor) {
			return '<p>' . esc_html__( 'Error rendering AskAI block: ', 'dappier' ) . esc_html( $e->getMessage() ) . '</p>';
		}
		return '';
	}
}
