<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 *
 * @since 1.0.0
 */
namespace azurecurve\RemoveRevisions;

/**
 * Create Cron hourly check for removing revisions.
 *
 * @since 1.0.0
 */
function create_cron() {

	$options = get_option_with_defaults( 'azrcrv-rr' );

	if ( $options['cron']['enabled'] == 1 ) {
		wp_schedule_event( strtotime( $options['cron']['time']['hour'] . ':' . $options['cron']['time']['minute'] . ':00' ), 'daily', 'azrcrv_rr_cron' );
	}

}

/**
 * Clear Cron hourly check for removing revisions.
 *
 * @since 1.0.0
 */
function clear_cron() {

	wp_clear_scheduled_hook( 'azrcrv_rr_cron' );

}

/**
 * Execute cron.
 *
 * @since 1.0.0
 */
function execute_cron() {
	global $wpdb;

	$options = get_option_with_defaults( 'azrcrv-rr' );

	$post_types    = $options['post-types'];
	$in_post_types = array();
	foreach ( $post_types as $post_type => $value ) {
		$in_post_types[] = esc_attr( $post_type );
	}
	$in_post_types = array_map(
		function( $post_type ) {
								return "'" . esc_sql( $post_type ) . "'";
		},
		$in_post_types
	);
	$in_post_types = implode( ',', $in_post_types );

	$sql = 'SELECT 
				post.ID 
			FROM 
				%1$s AS post 
			INNER JOIN 
				%1$s AS parent 
					ON 
						parent.ID = post.post_parent 
			WHERE 
				post.post_type = \'revision\' 
			AND 
				parent.post_type IN (' . $in_post_types . ') 
			AND 
				post.post_date <= (now() - interval %2$d %3$s)';

	// phpcs:ignore.preparingsql
	$prepared_sql = $wpdb->prepare( $sql, esc_sql( $wpdb->prefix . 'posts' ), esc_sql( $options['months-old'] ), esc_sql( $options['range-type'] ) );

	// phpcs:ignore.preparedsql
	$post_ids = $wpdb->get_col( $prepared_sql );
	if ( $post_ids ) {
		foreach ( $post_ids as $post_id ) {
			wp_delete_post_revision( (int) $post_id );
		}
	}

}
