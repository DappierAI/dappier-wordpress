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
 * @since 0.9.0
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
		// Check if we're in the block editor.
		$editor = defined('REST_REQUEST') && true === REST_REQUEST && 'edit' === filter_input( INPUT_GET, 'context', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

		// Build error function.
		$error = function( $message ) {
			return '<p style="padding:8px 12px;background-color:#f8d7da;color:#721c24;border:1px solid #f5c6cb;border-radius:4px;">' . $message . '</p>';
		};

		// Bail if not configured.
		if ( ! function_exists( 'dappier_is_configured' ) || ! dappier_is_configured() ) {
			if ( $editor ) {
				return $error( 'Dappier is not properly configured. Please go to the Dappier settings page to configure it.' );
			}

			return '';
		}

		// Filter out empty string attributes.
		$attributes = array_filter( $attributes, function( $value ) {
			return '' !== $value;
		} );

		// Force transparent when no color is set.
		if ( ! isset( $attributes['mainBackgroundColor'] ) || empty( $attributes['mainBackgroundColor'] ) ) {
			$attributes['mainBackgroundColor'] = 'transparent';
		}

		// Force these defaults when using the block.
		$attributes['mainLogoUrl'] = '';
		$attributes['enableTitle'] = false;

		// If in the editor, use dummy mode.
		if ( $editor ) {
			$attributes['mode'] = 'dummy';
		}

		// Instantiate the AskAI class with block attributes.
		$askai = new Dappier_AskAi( $attributes );

		if ( $editor ) {
 			$askai->set_location( 'editor' );
		} else {
			$askai->set_location( 'block' );
		}

		// Get the rendered HTML.
		$html = $askai->render();

		// Ensure we're returning a string.
		if ( empty( $html ) ) {
			if ( $editor ) {
				return $error( 'The Dappier AskAI module is not rendering. Please check your configuration.' );
			}

			return '';
		}

		return $html;

	} catch ( Exception $e ) {
		if ( $editor) {
			return $error( 'Error rendering AskAI block: ' . $e->getMessage() );
		}
		return '';
	}
}
