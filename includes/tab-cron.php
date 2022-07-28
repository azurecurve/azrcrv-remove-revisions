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

$tab_cron_label = esc_html__( 'Cron Settings', 'azrcrv-rr' );
$tab_cron       = '
<table class="form-table azrcrv-settings">
	
	<tr>

		<th scope="row">
		
			<label for="enable-cron">
			
				' . __( 'Enable Cron', 'azrcrv-rr' ) . '
				
			</label>
		</th>
			
		<td>
		
			<input name="enable-cron" type="checkbox" id="enable-cron" value="1" ' . checked( '1', $options['cron']['enabled'], false ) . ' />
			<label for="enable-cron">
				' . __( 'Cron will run daily at the time selected below.', 'azrcrv-rr' ) . '
			</label>
			
		</td>
		
	</tr>

	<tr>
		<th scope="row">
		
			' . esc_html__( 'Time', 'azrcrv-rr' ) . '
			
		</th>
		
		<td>
		
			<input type="number" min=0 max=23 step=1 name="cron-time-hour" style="width: 50px; " value="' . substr( '0' . $options['cron']['time']['hour'], -2 ) . '" onchange="if(parseInt(this.value,10)<10)this.value=\'0\'+this.value;" />:<input type="number" min=0 max=59 step=1 name="cron-time-minute" style="width: 50px; " value="' . substr( '0' . $options['cron']['time']['minute'], -2 ) . '" onchange="if(parseInt(this.value,10)<10)this.value=\'0\'+this.value;" />
			
		</td>
		
	</tr>

</table>';
