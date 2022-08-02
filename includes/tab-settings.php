<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\RemoveRevisions;

/**
 * Settings tab.
 */

if ( $options['range-type'] == 'day' ) {
	$range_days   = 'selected=selected';
	$range_months = '';
} else {
	$range_days   = '';
	$range_months = 'selected=selected';
}

$tab_settings_label = PLUGIN_NAME . ' ' . esc_html__( 'Settings', 'azrcrv-rr' );
$tab_settings       = '
<table class="form-table azrcrv-settings">
		
	<tr>
	
		<th scope="row">
			
				' . esc_html__( 'Age to remove', 'azrcrv-rr' ) . '
			
		</th>
	
		<td>
			
			<input name="months-old" type="number" min="1" id="months-old" value="' . sanitize_text_field( $options['months-old'] ) . '" class="small-text" />
			
			<select name="range-type">
				<option value="day" ' . $range_days . '>days</option>
				<option value="month" ' . $range_months . '>months</option>
			</select>
			
			<p class="description">
				' . esc_html__( 'Revisions older than this number of days/months will be removed.', 'azrcrv-rr' ) . '
			</label>
		
		</td>

	</tr>
	
	<tr>
		<th scope="row" colspan=2>
			
				' . __( 'Select the post types which are to have their revisions removed.', 'azrcrv-rr' ) . '
			
		</th>
		
	</tr>';


	$posttypes = get_post_types( array(), 'objects' );
	foreach ( $posttypes as $posttype ) {
		
		if ( $posttype->name != 'revision' ) {
		
			if ( isset( $options['post-types'][ $posttype->name ] ) ) {
				$current_setting = $options['post-types'][ $posttype->name ];
			} else {
				$current_setting = 0;
			}
			
			$tab_settings .= '<tr>
								<th scope="row">
									
										&nbsp;
									
								</th>
							
								<td>
								
									<input name="post_types[' . $posttype->name . ']" type="checkbox" id="post_types[' . $posttype->name . ']" value="1" ' . checked( '1', $current_setting, false ) . ' />
									<label for="post_types[' . $posttype->name . ']">
										' . $posttype->labels->name . '
									</label>
								
								</td>
			
							</tr>';

		}
	
	}

$tab_settings .= '</table>';
