<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * Adds settings page.
 *
 * @since 0.1.0
 */
class Dappier_AskAi {
	protected $args;
	protected $location;

	/**
	 * Construct the class.
	 *
	 * @since 0.1.0
	 *
	 * @param array $args The arguments.
	 *
	 * @return void
	 */
	function __construct( $args = [] ) {
		$args  = wp_parse_args( $args, $this->get_attributes() );
		$final = [];

		// Sanitize.
		foreach ( $args as $key => $value ) {
			$final[ esc_attr( $key ) ] = esc_attr( $value );
		}

		// Set the args.
		$this->args = $final;
	}

	/**
	 * Set the location.
	 *
	 * @since 0.7.0
	 *
	 * @return void
	 */
	function set_location( $location ) {
		$this->location = trim( preg_replace( '/[^a-z]+/', '_', strtolower( $location ) ), '_' );
	}

	/**
	 * Get the location.
	 *
	 * @since 0.7.0
	 *
	 * @return string
	 */
	function get_location() {
		return $this->location;
	}

	/**
	 * Get the args.
	 *
	 * @since 0.7.0
	 *
	 * @return array
	 */
	function get_args() {
		return $this->args;
	}

	/**
	 * Get an AskAI instance.
	 *
	 * @since 0.7.0
	 *
	 * @return void
	 */
	function render() {
		$html = '';

		// Bail if not configured.
		if ( ! dappier_is_configured() ) {
			return $html;
		}

		// Enqueue the instance.
		dappier_enqueue_loader();

		// Start attributes.
		$attributes = $this->args;

		// Start attributes.
		$attr = '';

		// Build attributes.
		foreach ( $attributes as $key => $value ) {
			$attr .= sprintf( ' %s="%s"', $key, $value );
		}

		// Add the HTML.
		$html .= sprintf( '<dappier-ask-ai-widget%s></dappier-ask-ai-widget>', $attr );

		// Allow filtering of HTML.
		$html = apply_filters( 'dappier_askai_html', $html, $this );

		// Return the instance.
		return $html;
	}

	/**
	 * Get attributes.
	 *
	 * @since 0.7.0
	 *
	 * @return array
	 */
	function get_attributes() {
		$widget_id   = dappier_get_option( 'widget_id' );
		$widget_id   = $widget_id;
		$bg_color    = dappier_get_option( 'askai_bg_color' );
		$bg_color    = $bg_color ?: 'inherit';
		$fg_color    = dappier_get_option( 'askai_fg_color' );
		$fg_color    = $fg_color ?: 'inherit';
		$theme_color = dappier_get_option( 'askai_theme_color' );
		$theme_color = $theme_color ?: 'inherit';
		$branding    = dappier_get_option( 'askai_branding' );
		$branding    = is_null( $branding ) ? 'logo' : $branding;
		$image_size  = has_image_size( 'medium' ) ? 'medium' : 'full';
		$logo_id     = dappier_get_option( 'askai_logo' );
		$logo_id     = ! $logo_id ? (int) get_theme_mod( 'custom_logo' ) : $logo_id;
		$logo_url    = $logo_id ? wp_get_attachment_image_url( $logo_id, $image_size ) : 'https://assets.dappier.com/dappier_logo.png';
		$logo_url    = 'logo' === $branding ? $logo_url : '';
		$logo_width  = dappier_get_option( 'askai_logo_width' );
		$logo_width  = $logo_width ?: '90';
		$title_text  = dappier_get_option( 'askai_title' );
		$icon_id     = dappier_get_option( 'askai_icon' );
		$icon_id     = is_null( $icon_id ) ? (int) get_option( 'site_icon' ) : $icon_id;
		$icon_url    = $icon_id ? wp_get_attachment_image_url( $icon_id, $image_size ) : 'https://assets.dappier.com/dappier_logo_small.png';
		$icon_width  = dappier_get_option( 'askai_icon_width' );
		$icon_width  = $icon_width ?: '24';

		// Set attributes.
		$attributes = [
			'widgetId'                        => $widget_id,
			'title'                           => $title_text,
			'searchPlaceholderText'           => '',
			'askButtonText'                   => '',
			'mainLogoUrl'                     => $logo_url,
			'chatIconUrl'                     => $icon_url,
			'userChatIconColor'               => $fg_color,
			'askButtonTextColor'              => '',
			'askButtonBackgroundColor'        => '',
			'mainBackgroundColor'             => $bg_color,
			'themeColor'                      => $theme_color,
			'promptSuggestionBackgroundColor' => '',
			'promptSuggestionTextColor'       => '',
			'messageBackgroundColor'          => '',
			'messageTextColor'                => $fg_color,
			'searchBoxBackgroundColor'        => '',
			'searchBoxTextColor'              => '',
			'searchPlaceholderTextColor'      => '',
			'titleColor'                      => '',
			'contentRecSiteNameColor'         => '',
			'containerMarginDesktop'          => '',
			'containerMarginMobile'           => '',
			'containerPaddingDesktop'         => '1rem',
			'containerPaddingMobile'          => '.75rem',
			'containerRadius'                 => '',
			'promptSuggestionRadius'          => '',
			'searchBoxRadius'                 => '',
			'askButtonRadius'                 => '',
			'elementRadius'                   => '',
			'mainLogoWidthMobile'             => $logo_width,
			'chatIconWidthMobile'             => $icon_width,
			'mainLogoWidthDesktop'            => $logo_width,
			'chatIconWidthDesktop'            => $icon_width,
			'fontSizeHeaderMobile'            => '',
			'fontSizeDefaultMobile'           => '',
			'fontSizeHeaderDesktop'           => '',
			'fontSizeDefaultDesktop'          => '',
			'fixedHeight'                     => '', //mobile only.
			'maxHeight'                       => '', //desktop only.
			'maxHeightMobile'                 => '',
			'heightModeMobile'                => 'max', // fixed or max, default is fixed if empty.
			'enableTitle'                     => 'title' === $branding ? 'true' : 'false',
			'enablePromptSuggestions'         => 'true',
			'enableContentRecommendations'    => 'true',
			'enableRelatedContentNewWindow'   => 'false', // open recommended content in new window
			'enableSiteName'                  => 'true', // for content recommendation.
			'referringUrl'                    => '',
			'initialSearchQuery'              => is_search() ? get_search_query() : '',
			'showInitialSearchQuery'          => 'false', // show the initial search query in the initial chat response.
			'disclaimerLink'                  => '',
		];

		// Allow filtering of attributes.
		$attributes = apply_filters( 'dappier_askai_attributes', $attributes );

		// Remove empty attributes.
		$attributes = array_filter( $attributes, function( $value ) {
			return '' !== $value && null !== $value;
		});

		return $attributes;
	}
}