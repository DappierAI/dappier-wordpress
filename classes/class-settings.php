<?php

// Prevent direct file access.
defined( 'ABSPATH' ) || die;

/**
 * Adds settings page.
 */
class Dappier_Settings {
	/**
	 * Construct the class.
	 */
	function __construct() {
		$this->hooks();
	}

	/**
	 * Add hooks.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function hooks() {
		add_action( 'admin_menu',                              [ $this, 'add_menu_item' ], 12 );
		add_action( 'admin_enqueue_scripts',                   [ $this, 'enqueue_script' ] );
		add_action( 'admin_init',                              [ $this, 'init' ] );
		add_filter( 'pre_update_option_dappier',               [ $this, 'before_update_settings' ], 10, 3 );
		add_action( 'update_option_dappier',                   [ $this, 'after_update_settings' ], 10, 2 );
		add_filter( 'plugin_action_links_dappier/dappier.php', [ $this, 'add_plugin_links' ], 10, 4 );
	}

	/**
	 * Adds menu item for settings page.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function add_menu_item() {
		// Add top-level menu for Dappier.
		add_menu_page(
			__( 'Dappier', 'dappier' ), // Page title
			__( 'Dappier', 'dappier' ), // Menu title
			'manage_options', // Capability
			'dappier', // Menu slug
			[ $this, 'add_content' ], // Function to display content
			'data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDEzNy40NCAxNjAiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDEzNy40NCAxNjA7IiB4bWw6c3BhY2U9InByZXNlcnZlIj48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNjY2MiIHN0cm9rZS13aWR0aD0iNSIgZD0iTTQyLjE3LDIyLjk4bDMxLjQ2LDM1LjY1Ii8+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjY2NjIiBzdHJva2Utd2lkdGg9IjUiIGQ9Ik0xMjMuNiwyNC40MWwtMTYuNzgsMzMuNTYiLz48cGF0aCBmaWxsPSJub25lIiBzdHJva2U9IiNjY2MiIHN0cm9rZS13aWR0aD0iNSIgZD0iTTMxLjQ2LDg0LjY1aDI3LjI2Ii8+PHBhdGggZmlsbD0ibm9uZSIgc3Ryb2tlPSIjY2NjIiBzdHJva2Utd2lkdGg9IjUiIGQ9Ik03Ny45MSwxNDcuODRsMTAuNDktMzcuNzUiLz48cGF0aCBmaWxsPSIjZDRkNGQ0IiBkPSJNMTE1LjM1LDE5LjIzYzAsNS43OSw0LjY5LDEwLjQ5LDEwLjQ5LDEwLjQ5YzUuNzksMCwxMC40OS00LjY5LDEwLjQ5LTEwLjQ5cy00LjctMTAuNDktMTAuNDktMTAuNDkgUzExNS4zNSwxMy40NCwxMTUuMzUsMTkuMjN6Ii8+PHBhdGggZmlsbD0iI2Q0ZDRkNCIgZD0iTTY3LjExLDE0OS4yN2MwLDUuNzksNC42OSwxMC40OSwxMC40OSwxMC40OXMxMC40OS00LjcsMTAuNDktMTAuNDlzLTQuNjktMTAuNDktMTAuNDktMTAuNDkgUzY3LjExLDE0My40OCw2Ny4xMSwxNDkuMjd6Ii8+PHBhdGggZmlsbD0iI2Q0ZDRkNCIgZD0iTTIwLjYyLDI1LjE3YzAsMTMuOSwxMS4yNywyNS4xNywyNS4xNywyNS4xN3MyNS4xNy0xMS4yNywyNS4xNy0yNS4xN1M1OS42OSwwLDQ1Ljc4LDBTMjAuNjIsMTEuMjcsMjAuNjIsMjUuMTcgeiIvPjxwYXRoIGZpbGw9IiNkNGQ0ZDQiIGQ9Ik0wLDg0LjI1YzAsOS4yNyw3LjUxLDE2Ljc4LDE2Ljc4LDE2Ljc4czE2Ljc4LTcuNTEsMTYuNzgtMTYuNzhzLTcuNTEtMTYuNzgtMTYuNzgtMTYuNzhTMCw3NC45OCwwLDg0LjI1eiIvPjxsaW5lYXJHcmFkaWVudCBpZD0iU1ZHSURfMV8iIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIiB4MT0iNjMuNzA2NiIgeTE9IjExMi4zMzExIiB4Mj0iMTQwLjQ4MDYiIHkyPSIzNS40MTMxIiBncmFkaWVudFRyYW5zZm9ybT0ibWF0cml4KDEgMCAwIC0xIDAgMTYyKSI+PHN0b3Agb2Zmc2V0PSIwIiBzdHlsZT0ic3RvcC1jb2xvcjojQ0Q2MkZGIi8+PHN0b3Agb2Zmc2V0PSIxIiBzdHlsZT0ic3RvcC1jb2xvcjojNkJCQ0ZGIi8+PC9saW5lYXJHcmFkaWVudD48cGF0aCBmaWxsPSJ1cmwoI1NWR0lEXzFfKSIgZD0iTTEyMi45NSw1NC45OGM4LDAsMTQuNDksNi4yLDE0LjQ5LDEzLjg2djMzLjM5YzAsNS45MS01LjAxLDEwLjcxLTExLjIsMTAuNzFjLTEuODIsMC0zLjI5LDEuNDEtMy4yOSwzLjE1IHYxMC43MWMwLDEuNDgtMC45MSwyLjgzLTIuMzIsMy40NGMtMS40MSwwLjYxLTMuMDcsMC4zOC00LjIzLTAuNmwtMTguMS0xNS4xNGMtMS4yMi0xLjAxLTIuNzUtMS41Ni00LjM0LTEuNTZINzIuOSBjLTgsMC0xNC40OS02LjIxLTE0LjQ5LTEzLjg2VjY4Ljg0YzAtNy42NSw2LjQ5LTEzLjg2LDE0LjQ5LTEzLjg2QzcyLjksNTQuOTgsMTIyLjk1LDU0Ljk4LDEyMi45NSw1NC45OHoiLz48L3N2Zz4=',
			56 // Position
		);
	}

	/**
	 * Enqueue script for select2.
	 *
	 * @since 0.7.0
	 *
	 * @return void
	 */
	function enqueue_script() {
		$screen = get_current_screen();

		// Bail if not our settings screen.
		if ( 'toplevel_page_dappier' !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'dappier-settings', dappier_get_file_url( 'dappier-settings', 'css' ), [], DAPPIER_PLUGIN_VERSION );
		wp_enqueue_script( 'dappier-settings', dappier_get_file_url( 'dappier-settings', 'js' ), [ 'jquery', 'wp-color-picker' ], DAPPIER_PLUGIN_VERSION, true );
	}

	/**
	 * Initialize the settings.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function init() {
		register_setting(
			'dappier', // option_group
			'dappier', // option_name
			[ $this, 'sanitize' ] // sanitize_callback
		);

		/************
		 * Fields   *
		 ************/

		// API Key.
		add_settings_field(
			'api_key', // id
			__( 'API Key', 'dappier' ), // title
			[ $this, 'api_key_callback' ], // callback
			'dappier', // page
			'dappier_two' // section
		);

		// Agent AI Model ID.
		add_settings_field(
			'aimodel_id', // id
			'', // title
			[ $this, 'aimodel_id_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Agent Data Model ID.
		add_settings_field(
			'datamodel_id', // id
			'', // title
			[ $this, 'datamodel_id_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Agent Widget ID.
		add_settings_field(
			'widget_id', // id
			'', // title
			[ $this, 'widget_id_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Agent Name.
		add_settings_field(
			'agent_name', // id
			'', // title
			[ $this, 'agent_name_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Agent Description.
		add_settings_field(
			'agent_desc', // id
			'', // title
			[ $this, 'agent_desc_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Agent Description.
		add_settings_field(
			'agent_persona', // id
			'', // title
			[ $this, 'agent_persona_callback' ], // callback
			'dappier', // page
			'dappier_three' // section
		);

		// Module Location.
		add_settings_field(
			'askai_location', // id
			'', // title
			[ $this, 'askai_location_callback' ], // callback
			'dappier', // page
			'dappier_four' // section
		);

		// Module Background Color.
		add_settings_field(
			'askai_bg_color', // id
			'', // title
			[ $this, 'askai_bg_color_callback' ], // callback
			'dappier', // page
			'dappier_four' // section
		);

		// Module Text Color.
		add_settings_field(
			'askai_fg_color', // id
			'', // title
			[ $this, 'askai_fg_color_callback' ], // callback
			'dappier', // page
			'dappier_four' // section
		);

		// Module Theme Color.
		add_settings_field(
			'askai_theme_color', // id
			'', // title
			[ $this, 'askai_theme_color_callback' ], // callback
			'dappier', // page
			'dappier_four' // section
		);
	}

	/**
	 * Sanitized saved values.
	 *
	 * @param array $input
	 *
	 * @return array
	 */
	function sanitize( $input ) {
		$allowed = [
			'api_key'            => 'sanitize_text_field',
			'aimodel_id'         => 'sanitize_text_field',
			'datamodel_id'       => 'sanitize_text_field',
			'widget_id'          => 'sanitize_text_field',
			'agent_name'         => 'sanitize_text_field',
			'agent_desc'         => 'sanitize_textarea_field',
			'agent_persona'      => 'sanitize_textarea_field',
			'askai_location'    => 'sanitize_text_field',
			'askai_bg_color'    => 'sanitize_text_field',
			'askai_fg_color'    => 'sanitize_text_field',
			'askai_theme_color' => 'sanitize_text_field',
		];

		// Get an array of matching keys from $input.
		$input = array_intersect_key( $input, $allowed );

		// Sanitize.
		foreach ( $input as $key => $value ) {
			$input[ $key ] = $allowed[ $key ]( $value );
		}

		return $input;
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function api_key_callback() {
		echo '<div class="dappier-step__field">';
			printf( '<input class="dappier-step__input" type="password" name="dappier[api_key]" id="api_key" value="%s">', dappier_get_option( 'api_key' ) );
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function aimodel_id_callback() {
		$aimodel_id = dappier_get_option( 'aimodel_id' );
		$agents     = $this->get_agents();

		echo '<div class="dappier-step__field">';
			printf( '<label class="dappier-step__label" for="dappier[aimodel_id]">%s</label>', __( 'Agent', 'dappier' ) );
			printf( '<p class="dappier-step__desc">%s</p>', __( 'Select an existing agent or create a new one.', 'dappier' ) );

			echo '<select class="dappier-step__input" name="dappier[aimodel_id]" id="aimodel_id">';
				// If agents.
				if ( $agents ) {
					// Add default option.
					echo '<option value="">Select an Agent</option>';

					// Add existing agents.
					foreach ( $agents as $agent ) {
						// SKip if id and name are not set.
						if ( ! isset( $agent['id'], $agent['name'] ) ) {
							continue;
						}

						$selected = $aimodel_id === $agent['id'] ? ' selected' : '';
						printf( '<option value="%s"%s>%s</option>', $agent['id'], $selected, $agent['name'] );
					}
				}

				// Add option to create a new agent.
				echo '<option value="_create_agent">Create a new Agent</option>';
			echo '</select>';
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.5.3
	 *
	 * @return void
	 */
	function datamodel_id_callback() {
		$datamodel_id = dappier_get_option( 'datamodel_id' );

		printf( '<input type="hidden" name="dappier[datamodel_id]" id="datamodel_id" value="%s">', esc_attr( $datamodel_id ) );
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.5.2
	 *
	 * @return void
	 */
	function widget_id_callback() {
		$widget_id = dappier_get_option( 'widget_id' );

		printf( '<input type="hidden" name="dappier[widget_id]" id="widget_id" value="%s">', esc_attr( $widget_id ) );
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function agent_name_callback() {
		$value = dappier_get_option( 'agent_name' );
		$value = ! $this->get_agents() ? get_bloginfo( 'name' ) : $value;

		echo '<div class="dappier-step__field agent_name">';
			printf( '<label class="dappier-step__label" for="dappier[agent_name]">%s</label>', __( 'Name (required)', 'dappier' ) );
			printf( '<p class="dappier-step__desc">%s</p>', __( 'Give your AI agent a name.', 'dappier' ) );
			printf( '<input class="dappier-step__input" type="text" name="dappier[agent_name]" id="agent_name" placeholder="%s" value="%s">',
				get_bloginfo( 'name' ),
				$value
			);
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function agent_desc_callback() {
		$value = dappier_get_option( 'agent_desc' );
		$value = ! $this->get_agents() ? get_bloginfo( 'description' ) : $value;

		echo '<div class="dappier-step__field agent_desc">';
			printf( '<label class="dappier-step__label" for="dappier[agent_desc]">%s</label>', __( 'Description (required)', 'dappier' ) );
			printf( '<p class="dappier-step__desc">%s</p>', __( 'Add a short description of what this AI Agent can do.', 'dappier' ) );
			printf( '<textarea id="agent_desc" class="dappier-step__input" name="dappier[agent_desc]" rows="3" placeholder="%s">%s</textarea>',
				__( 'You are a helpful guide on all the latest tech startup and technology news', 'dappier' ),
				$value
			);
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function agent_persona_callback() {
		$value = dappier_get_option( 'agent_persona' );

		echo '<div class="dappier-step__field agent_persona">';
			printf( '<label class="dappier-step__label" for="dappier[agent_persona]">%s</label>', __( 'Persona (required)', 'dappier' ) );
			printf( '<p class="dappier-step__desc">%s</p>', __( 'How should this AI Agent answer questions? What does it do? What should it not do?', 'dappier' ) );
			printf( '<textarea class="dappier-step__input" name="dappier[agent_persona]" id="agent_persona" rows="3" placeholder="%s">%s</textarea>',
				__( 'Use the available content sources and respond in a friendly manner.', 'dappier' ),
				$value
			);
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function askai_location_callback() {
		$value = dappier_get_option( 'askai_location' );

		echo '<div class="dappier-step__field">';
			printf( '<label class="dappier-step__label" for="dappier[askai_location]">%s</label>', __( 'Module Location', 'dappier' ) );
			printf( '<p class="dappier-step__desc">%s</p>', __( 'Select where you would like the AI askai to appear on your site. Use [dappier_askai] shortcode to manually display the askai.', 'dappier' ) );

			echo '<select class="dappier-step__input" name="dappier[askai_location]" id="askai_location">';
				$options = [
					''       => __( 'Disabled', 'dappier' ),
					'before' => __( 'Before Post Content', 'dappier' ),
					'after'  => __( 'After Post Content', 'dappier' ),
				];

				foreach ( $options as $key => $label ) {
					$selected = $value === $key ? ' selected' : '';
					printf( '<option value="%s"%s>%s</option>', $key, $selected, $label );
				}
			echo '</select>';
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function askai_bg_color_callback() {
		$value = dappier_get_option( 'askai_bg_color' );
		$value = ! is_null( $value ) ? $value : '#f8f9fa';

		echo '<div class="dappier-step__field">';
			printf( '<label class="dappier-step__label" for="dappier[askai_bg_color]">%s</label>', __( 'Module Background Color', 'dappier' ) );
			// printf( '<p class="dappier-step__desc">%s</p>', __( 'Set the background color for the AI askai.', 'dappier' ) );
			printf( '<input class="dappier-step__input dappier-color-picker" type="text" name="dappier[askai_bg_color]" id="askai_bg_color" value="%s">', $value );
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function askai_fg_color_callback() {
		$value = dappier_get_option( 'askai_fg_color' );

		echo '<div class="dappier-step__field">';
			printf( '<label class="dappier-step__label" for="dappier[askai_fg_color]">%s</label>', __( 'Module Text Color', 'dappier' ) );
			// printf( '<p class="dappier-step__desc">%s</p>', __( 'Set the text color for the AI askai.', 'dappier' ) );
			printf( '<input class="dappier-step__input dappier-color-picker" type="text" name="dappier[askai_fg_color]" id="askai_fg_color" value="%s">', $value );
		echo '</div>';
	}

	/**
	 * Setting callback.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function askai_theme_color_callback() {
		$value = dappier_get_option( 'askai_theme_color' );
		$value = ! is_null( $value ) ? $value : '#674ad9';

		echo '<div class="dappier-step__field">';
			printf( '<label class="dappier-step__label" for="dappier[askai_theme_color]">%s</label>', __( 'Module Theme Color', 'dappier' ) );
			// printf( '<p class="dappier-step__desc">%s</p>', __( 'Set the theme color for the AI askai.', 'dappier' ) );
			printf( '<input class="dappier-step__input dappier-color-picker" type="text" name="dappier[askai_theme_color]" id="askai_theme_color" value="%s">', $value );
		echo '</div>';
	}

	/**
	 * Adds setting page content.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function add_content() {
		// Get status, API key, and details.
		$status     = isset( $_GET['status'] ) && $_GET['status'] ? sanitize_text_field( $_GET['status'] ) : '';
		$api_key    = dappier_get_option( 'api_key' );
		$aimodel_id = dappier_get_option( 'aimodel_id' );
		$details    = $this->get_details();
		$active     = null;

		// Check status and set active.
		switch ( $status ) {
			case 'active':
				$active = true;
			break;
			case 'inactive':
				$active = false;
			break;
			break;
			default:
				// Active if no API key, and either no details or details that are not an error.
				$active = $api_key && ( ! $details || ( $details && ! is_wp_error( $details ) ) );
		}

		// Start the wrap.
		echo '<div class="wrap">';

			// Header.
			echo '<div class="dappier-header">';
				printf( '<p class="dappier-logo"><img class="dappier-logo__img" src="%s" alt="%s" /></p>', DAPPIER_PLUGIN_URL . 'src/img/dappier-logo.svg', __( 'Dappier Logo', 'dappier' ) );
				echo '<div class="dappier-header__content">';
					printf( '<p>%s: %s</p>', __( 'Version', 'dappier' ), DAPPIER_PLUGIN_VERSION );
				echo '</div>';
			echo '</div>';

			// Add heading. This is necessary because WP's JS moves notices after the first h2.
			printf( '<h2>%s</h2>', __( 'Dappier Plugin Settings', 'dappier' ) );

			// Display settings notices here.
			settings_errors();

			/**
			 * TEMP:
			 * @link https://app.visily.ai/projects/c10889df-54a3-4691-a3e0-cf551acd0183/boards/882115
			 */

			// Start inner classes.
			$classes = 'dappier-inner';

			// Add classes based on status.
			if ( $active ) {
				$classes .= ' dappier-inner__active';
			} else {
				$classes .= ' dappier-inner__inactive';
			}

			// Inner.
			printf( '<div class="%s">', $classes );
				// Start form.
				echo '<form class="dappier-form" method="post" action="options.php">';
					// If forced inactive.
					if ( ! $active && dappier_get_option( 'api_key' ) ) {
						echo '<div class="dappier-callout">';
							echo '<div style="display:flex;flex-wrap:nowrap;justify-content:space-between;align-items:center;gap:36px;">';
								printf( '<p style="margin:0;font-size:1.25em;text-wrap:balance;"><strong>%s</strong></p>', __( 'Your API key is saved, go configure your settings!', 'dappier' ) );
								printf( '<a href="%s" class="button button-primary">%s</a>', admin_url( 'admin.php?page=dappier' ), __( 'Configure Now →', 'dappier' ) );
							echo '</div>';
						echo '</div>';
					}

					// Step 1.
					echo '<div class="dappier-step dappier-step__one">';
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Step 1 | Create a Dappier Account', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'By creating a free Dappier account, you will be able to:', 'dappier' ) );

								echo '<ul style="list-style:disc inside;">';
									printf( '<li>' . __( '%1$sEngage your audience%2$s with AI-powered chat and assistance across your website content.', 'dappier' ) . '</li>', '<strong>', '</strong>' );
									printf( '<li>' . __( '%1$sCombat traffic loss%2$s due to scrapers and %1$sincrease traffic%2$s through AI-driven content recommendations and recirculation.', 'dappier' ) . '</li>', '<strong>', '</strong>' );
									printf( '<li>' . __( '%1$sGenerate additional revenue%2$s by syndicating your content free to Dappier\'s AI Marketplace.', 'dappier' ) . '</li>', '<strong>', '</strong>' );
									printf( '<li>' . __( '%1$sMonetize%2$s chat interactions with Dappier\'s highly relevant conversational, context-aware ads in your AI chat.', 'dappier' ) . '</li>', '<strong>', '</strong>' );
								echo '</ul>';

								printf( '<p><a href="https://dappier.com" target="_blank" rel="noopener">%s</a></p>', __( 'Learn more about Dappier', 'dappier' ) );
								printf( '<p><a href="https://platform.dappier.com/sign-in" class="button button-primary" target="_blank" rel="noopener">%s</a></p>', __( 'Create an Account', 'dappier' ) );

							echo '</div>';
						echo '</div>';
					echo '</div>';

					// Step 2.
					echo '<div class="dappier-step dappier-step__two">';
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Step 2 | Activate your account with your API key', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'Once you have created a Dappier account, activate this plugin to connect your site to Dappier and create an AI Agent.', 'dappier' ) );
								printf( '<p>%s</p>', __( 'To activate your plugin, enter your API Access key.', 'dappier' ) );
								printf( '<p><a href="https://platform.dappier.com/profile/api-keys" target="_blank" rel="noopener">%s</a></p>', __( 'Click here to get your API Key', 'dappier' ) );
								do_settings_fields( 'dappier', 'dappier_two');
								$button_text = $api_key ? __( 'Update API Key', 'dappier' ) : __( 'Save and Connect', 'dappier' );
								submit_button( $button_text, 'primary', 'submit_two' );

								// If error.
								if ( is_wp_error( $details ) ) {
									printf( '<p style="color:var(--color-error);">%s</p>', $details->get_error_message() );
								}
							echo '</div>';
						echo '</div>';
					echo '</div>';

					// Step 3.
					echo '<div class="dappier-step dappier-step__three">';
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Create or choose your default AI Agent', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'To get started, create or link an existing AI agent with your content.', 'dappier' ) );
								printf( '<p>%s</p>', __( 'Follow the steps below. The setup only takes a few minutes.', 'dappier' ) );
								printf( '<div class="dappier-callout">%s</div>', __( 'We\'ll securely generate a private AI model, stored in a multi-tenant system. This model will power the Ask AI chatbot and AI-powered recommendations on your site.', 'dappier' ) );
								do_settings_fields( 'dappier', 'dappier_three');
								$button_text = $aimodel_id ? __( 'Update Agent', 'dappier' ) : __( 'Save Agent', 'dappier' );
								submit_button( $button_text, 'primary', 'submit_three' );
							echo '</div>';
						echo '</div>';
					echo '</div>';

					// Step 4.
					echo '<div class="dappier-step dappier-step__four">';
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Configure your site', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'To get started, create or link an existing AI agent with your content.', 'dappier' ) );
								printf( '<p>%s</p>', __( 'Follow the steps below. The setup only takes a few minutes.', 'dappier' ) );
								// printf( '<p><a href="%s" class="button button-primary">%s</a></p>', '#', __( 'Configure Site', 'dappier' ) );
								do_settings_fields( 'dappier', 'dappier_four');
								submit_button( __( 'Update Settings', 'dappier' ), 'primary', 'submit_four' );
							echo '</div>';
						echo '</div>';
					echo '</div>';

					// Step 5.
					echo '<div class="dappier-step dappier-step__five">';
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Opt into Dappier Marketplace to syndicate & earn money for your content', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'Join our marketplace to earn money as your content is discovered and accessed by AI developers and LLMs that will compensate you on a pay-per-query (question) basis.', 'dappier' ) );
								printf( '<p><a href="https://docs.dappier.com/publish-and-monetize">%s</a></p>', __( 'Learn More', 'dappier' ) );
								if ( $aimodel_id ) {
									printf( '<p><a href="https://platform.dappier.com/my-ai-config/%s?tab=datamodel" class="button button-primary">%s</a></p>', $aimodel_id, __( 'Publish your data to Dappier\'s marketplace', 'dappier' ) );
								} else {
									printf( '<p><button class="button button-primary" disabled>%s</button></p>', __( 'Please create or choose your AI Agent above', 'dappier' ) );
								}
							echo '</div>';
						echo '</div>';
					echo '</div>';

					// Hidden fields.
					settings_fields( 'dappier' );
					printf( '<input type="hidden" name="status" value="%s">', $status );
				echo '</form>';

				// Sidebar.
				echo '<div class="dappier-inner__sidebar">';
					echo '<div class="dappier-step dappier-step_sidebar">';

						// If active.
						if ( $active ) {
							// My Account.
							echo '<div class="dappier-step__inner">';
								printf( '<h3 class="dappier-heading">%s</h3>', __( 'My Account', 'dappier' ) );
								echo '<div class="dappier-step__content">';
									// If agent is selected.
									if ( $aimodel_id ) {
										printf( '<p><strong>%s</strong></p>', __( 'Congratulations! You have linked your Dappier account.', 'dappier' ) );
										echo '<ul class="dappier-step__details">';
										foreach ( $details as $key => $value ) {
											printf( '<li><span>%s:</span> <span>%s</span></li>', $key, $value );
										}
										echo '</ul>';
									}
									// No agent.
									else {
										printf( '<p>%s</p>', __( 'Please create or choose your AI agent to link your Dappier account.', 'dappier' ) );
									}

									// Build url to update API key.
									$update_url = add_query_arg(
										[
											'status' => 'inactive',
										],
										admin_url( 'admin.php?page=dappier' )
									);

									// Update API Key.
									echo '<ul>';
										printf( '<li><a href="%s">> %s</a></li>', esc_url( $update_url ), __( 'Update API Key', 'dappier' ) );
									echo '</ul>';
								echo '</div>';
							echo '</div>';
						}

						// About Dappier.
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'About Dappier', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'Dappier is a platform that helps you engage your audience and monetize your content through AI-powered tools.', 'dappier' ) );
								echo '<ul>';
									printf( '<li><a rel="noopener" target="_blank" href="https://dappier.com">> %s</a></li>', __( 'Dappier Home', 'dappier' ) );
									printf( '<li><a rel="noopener" target="_blank" href="https://platform.dappier.com/subscription-plan">> %s</a></li>', __( 'Pricing', 'dappier' ) );
									printf( '<li><a rel="noopener" target="_blank" href="https://dappier.com/team/">> %s</a></li>', __( 'Who we are', 'dappier' ) );
								echo '</ul>';
							echo '</div>';
						echo '</div>';

						// Need Help?
						echo '<div class="dappier-step__inner">';
							printf( '<h3 class="dappier-heading">%s</h3>', __( 'Need Help?', 'dappier' ) );
							echo '<div class="dappier-step__content">';
								printf( '<p>%s</p>', __( 'Do you need assistance or more details?', 'dappier' ) );
								echo '<ul>';
									printf( '<li><a rel="noopener" target="_blank" href="https://docs.dappier.com/">> %s</a></li>', __( 'Docs', 'dappier' ) );
									printf( '<li><a rel="noopener" target="_blank" href="https://docs.dappier.com/embed-widgets">> %s</a></li>', __( 'Widgets', 'dappier' ) );
									printf( '<li><a rel="noopener" target="_blank" href="https://docs.dappier.com/bot-deterrence">> %s</a></li>', __( 'Scraper Bots Deterrence', 'dappier' ) );
								echo '</ul>';
							echo '</div>';
						echo '</div>';

					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}

	/**
	 * Get the account details.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	function get_details() {
		// Get required data.
		$details    = [];
		$api_key    = dappier_get_option( 'api_key' );
		$aimodel_id = dappier_get_option( 'aimodel_id' );

		// Bail missing data.
		if ( ! ( $api_key && $aimodel_id ) ) {
			return $details;
		}

		// Set up the transient name.
		$transient = 'dappier_details';

		// Check for transient.
		if ( false === ( $details = get_transient( $transient ) ) ) {
			// Set up the API url and body.
			$url        = "https://api.dappier.com/v1/integrations/account?aimodelid={$aimodel_id}";
			$args       = [
				'headers' => [
					'Authorization' => 'Bearer ' . $api_key,
				],
			];

			// Make the request.
			$response = wp_remote_get( $url, $args );
			$code     = wp_remote_retrieve_response_code( $response );
			$body     = wp_remote_retrieve_body( $response );

			// Check for errors.
			if ( 200 !== $code ) {
				$message = isset( $response['response']['message'] ) ? $response['response']['message'] : __( 'An error occurred while processing the request.', 'dappier' );
				$details = new WP_Error( 'dappier_details_error', $code . ' ' . $message );
			}
			// No errors.
			else {
				// Decode the body.
				$details = (array) json_decode( $body, true );
			}

			// Set the transient.
			set_transient( $transient, $details, MINUTE_IN_SECONDS * 5 );
		}

		// Bail if no details or error.
		if ( ! $details || is_wp_error( $details ) ) {
			return $details;
		}

		// Map the details.
		$map = [
			'account_id'           => [ 'label' => __( 'Account ID', 'dappier' ), 'sanitize' => 'sanitize_key' ],
			'widget_id'            => [ 'label' => __( 'AskAI ID', 'dappier' ), 'sanitize' => 'sanitize_key' ],
			'email'                => [ 'label' => __( 'Email', 'dappier' ), 'sanitize' => 'sanitize_email' ],
			'name'                 => [ 'label' => __( 'Name', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
			'subscription_level'   => [ 'label' => __( 'Subscription', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
			'ai_agents_used'       => [ 'label' => __( 'AI Agents', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
			'created_at'           => [ 'label' => __( 'Created at', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
			'queries_used_month'   => [ 'label' => __( 'Queries this Month', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
			'rev_share_in_percent' => [ 'label' => __( 'Revenue Share', 'dappier' ), 'sanitize' => 'sanitize_text_field' ],
		];

		// Loop and sanitize.
		foreach ( $map as $key => $data ) {
			// Skip if not set.
			if ( ! isset( $details[ $key ] ) ) {
				continue;
			}

			// Sanitize.
			$details[ $data['label'] ] = $data['sanitize']( $details[ $key ] );

			// Format.
			switch ( $key ) {
				case 'created_at':
					$timestamp                 = strtotime( $details[ $key ] );
					$date                      = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $timestamp );
					$details[ $data['label'] ] = $date;
				break;
				case 'rev_share_in_percent':
					$details[ $data['label'] ] .= '%';
				break;
			}

			// Unset the old key.
			unset( $details[ $key ] );
		}

		return $details;
	}

	/**
	 * Get the agents.
	 *
	 * @since 0.1.0
	 *
	 * @return array
	 */
	function get_agents() {
		// Get the API key.
		$api_key = dappier_get_option( 'api_key' );

		// Bail if no API key.
		if ( ! $api_key ) {
			return [];
		}

		// Set up the transient name.
		$transient = 'dappier_agents';

		// Check for transient.
		if ( false === ( $body = get_transient( $transient ) ) ) {
			// Set up the API url and body.
			$url  = 'https://api.dappier.com/v1/integrations/agent?type=wordpress';
			$args = [
				'headers' => [
					'Authorization' => 'Bearer ' . $api_key,
				],
			];

			// Make the request.
			$response = wp_remote_get( $url, $args );
			$code     = wp_remote_retrieve_response_code( $response );
			$body     = wp_remote_retrieve_body( $response );
			$body     = json_decode( $body, true );

			// Check for errors.
			if ( 200 !== $code ) {
				// Get message.
				$message = isset( $response['response']['message'] ) ? $response['response']['message'] : __( 'An error occurred while processing the request.', 'dappier' );

				// Add a settings error with a unique error code.
				add_settings_error(
					'dappier',
					'get_agent_error_' . $code,
					sprintf( __( 'Error Getting Agents (%d): %s', 'dappier' ), $code, $message ),
					'error'
				);
			}

			// Set the transient.
			set_transient( $transient, $body, MINUTE_IN_SECONDS * 5 );
		}

		// If we have agents.
		if ( $body ) {
			// Get domains.
			$site_host = wp_parse_url( home_url(), PHP_URL_HOST );

			// Loop and unset if not the same domain.
			foreach ( $body as $index => $agent ) {
				$feed_url  = isset( $agent['feed_url'] ) ? $agent['feed_url'] : null;
				$feed_host = $feed_url ? wp_parse_url( $feed_url, PHP_URL_HOST ) : null;

				// Skip if no feed host.
				if ( ! $feed_host ) {
					continue;
				}

				// Unset if not the same.
				if ( $feed_host !== $site_host ) {
					unset( $body[ $index ] );
				}
			}
		}

		return $body;
	}

	/**
	 * Delete transients and maybe create agent.
	 *
	 * @since 0.1.0
	 *
	 * @param mixed  $value     The new, unserialized option value.
	 * @param mixed  $old_value The old option value.
	 * @param string $option    Option name.
	 *
	 * @return void
	 */
	function before_update_settings( $value, $old_value, $option ) {
		// Bail if not our option.
		if ( 'dappier' !== $option ) {
			return $value;
		}

		// Bail if no value.
		if ( ! ( is_array( $value ) && $value ) ) {
			return $value;
		}

		// Get all transients to delete.
		$transients = [
			'dappier_details',
			'dappier_agents',
		];

		// Loop and delete.
		foreach ( $transients as $transient ) {
			delete_transient( $transient );
		}

		// Get the API key.
		$api_key = isset( $value['api_key'] ) ? $value['api_key'] : '';

		// Bail if no API key.
		if ( ! $api_key ) {
			return $value;
		}

		// Get the data.
		$aimodel_id   = isset( $value['aimodel_id'] ) ? $value['aimodel_id'] : '';
		$datamodel_id = isset( $value['datamodel_id'] ) ? $value['datamodel_id'] : '';
		$name         = isset( $value['agent_name'] ) ? $value['agent_name'] : '';
		$desc         = isset( $value['agent_desc'] ) ? $value['agent_desc'] : '';
		$pers         = isset( $value['agent_persona'] ) ? $value['agent_persona'] : '';

		// If we have a model, and it's not creating a new one.
		if ( $aimodel_id && '_create_agent' !== $aimodel_id ) {
			$old_datamodel_id   = isset( $old_value['datamodel_id'] ) ? $old_value['datamodel_id'] : '';
			$new_datamodel_id   = isset( $value['datamodel_id'] ) ? $value['datamodel_id'] : '';
			$old_widget_id      = isset( $old_value['widget_id'] ) ? $old_value['widget_id'] : '';
			$new_widget_id      = isset( $value['widget_id'] ) ? $value['widget_id'] : '';
			$needs_datamodel_id = ! $new_datamodel_id || $old_datamodel_id !== $new_datamodel_id;
			$needs_widget_id    = ! $new_widget_id || $old_widget_id !== $new_widget_id;

			// If we need a data model or widget id.
			if ( ! $needs_datamodel_id || ! $needs_widget_id ) {
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
				$response = wp_remote_get( $url, $args );
				$code     = wp_remote_retrieve_response_code( $response );

				// Check for errors.
				if ( 200 === $code ) {
					$body = wp_remote_retrieve_body( $response );
					$body = json_decode( $body, true );

					if ( $body ) {
						if ( $needs_datamodel_id && isset( $body['datamodel_id'] ) ) {
							$value['datamodel_id'] = $body['datamodel_id'];
						}

						if ( $needs_widget_id && isset( $body['widget_id'] ) ) {
							$value['widget_id'] = $body['widget_id'];
						}
					}
				}
			}
		}

		// Unset the agent data.
		unset( $value['agent_name'], $value['agent_desc'], $value['agent_persona'] );

		// Bail if not creating.
		if ( '_create_agent' !== $aimodel_id ) {
			return $value;
		}

		// Unset the agent.
		$value['aimodel_id'] = '';

		// Bail if no agent data.
		if ( ! ( $name && $desc && $pers ) ) {
			// Add a settings error with a unique error code.
			add_settings_error(
				'dappier',
				'get_agent_error_create_agent',
				__( 'Error Creating Agent: All fields are required', 'dappier' ),
				'error'
			);

			return $value;
		}

		// Check for agent submission.
		$agent = $this->create_agent(
			[
				'api_key' => $api_key,
				'name'    => $name,
				'desc'    => $desc,
				'persona' => $pers,
			]
		);

		// Update the agent.
		if ( $agent && is_array( $agent ) ) {
			if ( isset( $agent['id'] ) ) {
				$value['aimodel_id'] = $agent['id'];
			}

			if ( isset( $agent['datamodel_id'] ) ) {
				$value['datamodel_id'] = $agent['datamodel_id'];
			}

			if ( isset( $agent['widget_id'] ) ) {
				$value['widget_id'] = $agent['widget_id'];
			}
		}

		return $value;
	}

	/**
	 * Create an agent.
	 *
	 * @since 0.1.0
	 *
	 * @param array $data The agent data.
	 *
	 * @return void
	 */
	function create_agent( $data ) {
		// Set up the API url and body.
		$url  = 'https://api.dappier.com/v1/integrations/agent';
		$body = [
			'name'           => $data['name'],
			'description'    => $data['desc'],
			'persona'        => $data['persona'],
			'type'           => 'wordpress',
			'feed_url'       => home_url( '/wp-json/dappier/v1/posts' ),
			'prompt_samples' => [],
		];

		// Set up the request arguments.
		$args = [
			'headers' => [
				'Content-Type'  => 'application/json',
				'Authorization' => 'Bearer ' . $data['api_key'],
			],
			'body' => wp_json_encode( $body ),
		];

		// Make the request.
		$response = wp_remote_post( $url, $args );
		$code     = wp_remote_retrieve_response_code( $response );
		$body     = wp_remote_retrieve_body( $response );
		$body     = json_decode( $body, true );

		// Check for errors.
		if ( 200 !== $code ) {
			// Get message.
			$message = isset( $response['response']['message'] ) ? $response['response']['message'] : __( 'An error occurred while processing the request.', 'dappier' );

			// Add a settings error with a unique error code.
			add_settings_error(
				'dappier',
				'create_agent_error_' . $code,
				sprintf( __( 'Error Creating Agent (%d): %s', 'dappier' ), $code, $message ),
				'error'
			);
		}

		return $body;
	}

	/**
	 * Redirect to main settings page after API key change.
	 * This entire hook won't fire if no options are changed.
	 *
	 * @since 0.2.0
	 *
	 * @param array $old_value
	 * @param array $new_value
	 *
	 * @return void
	 */
	function after_update_settings( $old_value, $new_value ) {
		// Bail if no status is set.
		if ( ! isset( $_POST['status'] ) || ! $_POST['status'] ) {
			return;
		}

		// Bail if we don't have the API key.
		if ( ! isset( $old_value['api_key'], $new_value['api_key'] ) ) {
			return;
		}

		// Bail if the API key is the same.
		if ( $old_value['api_key'] === $new_value['api_key'] ) {
			return;
		}

		// Redirect to main settings page, without query params.
		wp_safe_redirect( admin_url( 'admin.php?page=dappier' ) );
		exit;
	}

	/**
	 * Return the plugin action links.  This will only be called if the plugin is active.
	 *
	 * @since 0.1.0
	 *
	 * @param array  $actions     Associative array of action names to anchor tags.
	 * @param string $plugin_file Plugin file name, ie my-plugin/my-plugin.php.
	 * @param array  $plugin_data Associative array of plugin data from the plugin file headers.
	 * @param string $context     Plugin status context, ie 'all', 'active', 'inactive', 'recently_active'.
	 *
	 * @return array associative array of plugin action links.
	 */
	function add_plugin_links( $actions, $plugin_file, $plugin_data, $context ) {
		$settings = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=dappier' ) ), __( 'Settings', 'dappier' ) );
		$actions  = [ 'settings' => $settings ] + $actions;

		return $actions;
	}
}