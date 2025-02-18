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
		'render_callback' => 'render_block_dappier_askai',
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
function render_block_dappier_askai( $attributes, $content, $block ) {
	try {
		// Bail if not configured.
		if ( ! function_exists( 'dappier_is_configured' ) || ! dappier_is_configured() ) {
			if ( is_admin() ) {
				return '<p>' . esc_html__( 'Dappier is not configured. Please go to the Dappier settings page to configure it.', 'dappier' ) . '</p>';
			}

			return '';
		}

		// Ensure attributes are set.
		// $attributes = wp_parse_args( $attributes, [
		// 	'searchPlaceholderText'           => '',
		// 	'askButtonText'                   => '',
		// 	'askButtonTextColor'              => '',
		// 	'askButtonBackgroundColor'        => '',
		// 	'themeColor'                      => '',
		// 	'promptSuggestionBackgroundColor' => '',
		// 	'promptSuggestionTextColor'       => '',
		// 	'enablePromptSuggestions'         => true,
		// 	'enableContentRecommendations'    => true,
		// 	'showInitialSearchQuery'          => false,
		// ] );

		// // Convert boolean attributes to strings for the widget.
		// $attributes['enablePromptSuggestions'] = $attributes['enablePromptSuggestions'] ? 'true' : 'false';
		// $attributes['enableContentRecommendations'] = $attributes['enableContentRecommendations'] ? 'true' : 'false';
		// $attributes['showInitialSearchQuery'] = $attributes['showInitialSearchQuery'] ? 'true' : 'false';

		// Filter out empty string attributes.
		$attributes = array_filter( $attributes, function( $value ) {
			return '' !== $value;
		} );

		// Force the initial search query to the home URL when in the block editor.
		if ( is_admin() && ! $attributes['initialSearchQuery'] ) {
			$attributes['initialSearchQuery'] = home_url();
		}

		// Force transparent when no color is set.
		if ( ! $attributes['mainBackgroundColor'] ) {
			$attributes['mainBackgroundColor'] = 'transparent';
		}

		// Force these defaults when using the block.
		$attributes['mainLogoUrl'] = '';
		$attributes['mainLogoUrl'] = '';
		$attributes['enableTitle'] = 'false';

		// ray( 'after', $attributes );

		// Instantiate the AskAI class with block attributes.
		$askai = new Dappier_AskAi( $attributes );
		$askai->set_location( 'block' );

		// Get the rendered HTML.
		$html = $askai->render();

		// Ensure we're returning a string.
		if ( empty( $html ) ) {
			if ( is_admin() ) {
				return '<p>' . esc_html__( 'The AskAI widget is not rendering. Please check your configuration.', 'dappier' ) . '</p>';
			}

			return '';
		}

		return $html;

	} catch ( Exception $e ) {
		if ( is_admin() ) {
			return '<p>' . esc_html__( 'Error rendering AskAI block: ', 'dappier' ) . esc_html( $e->getMessage() ) . '</p>';
		}
		return '';
	}
}
