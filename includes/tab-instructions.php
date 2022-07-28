<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\RemoveRevisions;

/**
 * Instructions tab.
 */
$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-rr' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Remove Revision Settings', 'azrcrv-rr' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .
				sprintf( esc_html__( 'Configure how long revisions should be retained for; this can be specified in days or months (default is %d months).', 'azrcrv-rr' ), 6 ) . '
					
			</p>
		
			<p>' .
				esc_html__( 'Mark the box next to the post types which should have their revisions removed when they are older than the above specified age.', 'azrcrv-rr' ) . '
					
			</p>
		
			<p>' .
				sprintf( esc_html__( 'Click %1$sSave Settings%2$s to just save changes or %1$sSave Settings & Remove Revisions%2$s to perform an immediate deletion of revisions older than the specified age.', 'azrcrv-rr' ), '<strong>', '</strong>' ) . '
					
			</p>
		
		</td>
	
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Cron Settings', 'azrcrv-rr' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .
				esc_html__( 'Cron can be enabled or disabled as required by marking/unmarking the checkbox.', 'azrcrv-rr' ) . '
					
			</p>
			<p>' .
				esc_html__( 'If the cron is enabled, specify the time at which it should run daily.', 'azrcrv-rr' ) . '
					
			</p>
		
		</td>
	
	</tr>
	
</table>';
