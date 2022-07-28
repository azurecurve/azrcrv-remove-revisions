<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\RemoveRevisions;

/**
 * Get options including defaults.
 *
 * @since 1.2.0
 */
function get_option_with_defaults( $option_name ) {

	$defaults = array(
		'months-old' => 6,
		'range-type' => 'month',
		'cron'       => array(
			'enabled' => 0,
			'time'    => array(
				'hour'   => 4,
				'minute' => 0,
			),
		),
		'post-types' => array(),
	);

	$options = get_option( $option_name, $defaults );

	$options = recursive_parse_args( $options, $defaults );

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 *
 * @since 1.0.0
 */
function recursive_parse_args( $args, $defaults ) {
	$new_args = (array) $defaults;

	foreach ( $args as $key => $value ) {
		if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
			$new_args[ $key ] = recursive_parse_args( $value, $new_args[ $key ] );
		} else {
			$new_args[ $key ] = $value;
		}
	}

	return $new_args;
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 */
function display_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-rr' ) );
	}

	// Retrieve plugin configuration options from database.
	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	echo '<div id="' . esc_attr( PLUGIN_HYPHEN ) . '-general" class="wrap">';

		echo '<h1>';
			echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
			echo esc_html( get_admin_page_title() );
		echo '</h1>';

	// phpcs:ignore.
	if ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible">
					<p><strong>' . esc_html__( 'Settings have been saved.', 'azrcrv-rr' ) . '</strong></p>
				</div>';
	}

		require_once 'tab-settings.php';
		require_once 'tab-cron.php';
		require_once 'tab-instructions.php';
		require_once 'tab-other-plugins.php';
		require_once 'tabs-output.php';
	?>
		
	</div>
	<?php
}

/**
 * Save settings.
 *
 * @since 1.0.0
 */
function save_options() {
	// Check that user has proper security level.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action', 'azrcrv-rr' ) );
	}
	// Check that nonce field created in configuration form is present.
	if ( ! empty( $_POST ) && check_admin_referer( PLUGIN_HYPHEN, PLUGIN_HYPHEN . '-nonce' ) ) {

		// Retrieve original plugin options array.
		$options = get_option_with_defaults( PLUGIN_HYPHEN );

		$options['months-old'] = (int) $_POST['months-old'];

		$option_name = 'range-type';
		if ( isset( $_POST[ $option_name ] ) && $_POST[ $option_name ] == 'day' ) {
			$options[ $option_name ] = 'day';
		} else {
			$options[ $option_name ] = 'month';
		}

		// post types
		// phpcs:ignore.sanitized on next row
		$post_types            = isset( $_POST['post_types'] ) ? (array) $_POST['post_types'] : array();
		$post_types            = array_map( 'sanitize_text_field', $post_types );
		$options['post-types'] = $post_types;

		// cron enabled
		if ( isset( $_POST['enable-cron'] ) ) {
			$options['cron']['enabled'] = 1;
		} else {
			$options['cron']['enabled'] = 0;
		}
		// cron time hour
		$options['cron']['time']['hour'] = sanitize_text_field( intval( $_POST['cron-time-hour'] ) );
		// cron time minute
		$options['cron']['time']['minute'] = sanitize_text_field( intval( $_POST['cron-time-minute'] ) );

		// Store updated options array to database.
		update_option( PLUGIN_HYPHEN, $options );

		/*
		* Remove scheduled cron events
		*/
		wp_clear_scheduled_hook( 'azrcrv_rr_cron' );

		/*
		* Add scheduled cron event
		*/
		if ( $options['cron']['enabled'] == 1 ) {
					wp_schedule_event( strtotime( substr( '0' . $options['cron']['time']['hour'], -2 ) . ':' . substr( '0' . $options['cron']['time']['minute'], -2 ) . ':00' ), 'daily', 'azrcrv_rr_cron' );
		}

		if ( isset( $_POST['btn_save_remove'] ) ) {
			execute_cron();
		}

		// Redirect the page to the configuration form that was processed.
		wp_safe_redirect( add_query_arg( 'page', PLUGIN_HYPHEN . '&settings-updated', admin_url( 'admin.php' ) ) );
		exit;
	}
}
