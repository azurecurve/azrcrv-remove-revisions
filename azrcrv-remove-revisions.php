<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Remove Revisions
 * Description: Remove old post revisions over the specified months old.
 * Version: 1.0.3
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/remove-revisions/
 * Text Domain: remove-revisions
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/rrl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Declare the namespace.
namespace azurecurve\RemoveRevisions;

// Prevent direct access.
if (!defined('ABSPATH')){
	die();
}

// include plugin menu
require_once(dirname( __FILE__).'/pluginmenu/menu.php');
add_action('admin_init', 'azrcrv_create_plugin_menu_rr');

// include update client
require_once(dirname(__FILE__).'/libraries/updateclient/UpdateClient.class.php');

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 *
 */
// register activation hook
register_activation_hook(__FILE__, __NAMESPACE__.'\\create_cron');

// register deactivation hook
register_deactivation_hook(__FILE__, __NAMESPACE__.'\\clear_cron');

// add actions
add_action('admin_menu', __NAMESPACE__.'\\create_admin_menu');
add_action('admin_init', __NAMESPACE__.'\\register_admin_styles');
add_action('admin_enqueue_scripts', __NAMESPACE__.'\\enqueue_admin_styles');
add_action('admin_init', __NAMESPACE__.'\\register_admin_scripts');
add_action('admin_enqueue_scripts', __NAMESPACE__.'\\enqueue_admin_scripts' );
add_action('plugins_loaded', __NAMESPACE__.'\\load_languages');
add_action('admin_post_azrcrv_rr_save_options', __NAMESPACE__.'\\save_options');
add_action('azrcrv_rr_cron', __NAMESPACE__.'\\execute_cron');


// add filters
add_filter('plugin_action_links', __NAMESPACE__.'\\add_plugin_action_link', 10, 2);
add_filter('codepotent_update_manager_image_path', __NAMESPACE__.'\\custom_image_path');
add_filter('codepotent_update_manager_image_url', __NAMESPACE__.'\\custom_image_url');

/**
 * Create Cron hourly check for widget announcements.
 *
 * @since 1.0.0
 *
 */
function create_cron(){
	
	$options = get_option_with_defaults('azrcrv-rr');
	
	if ($options['cron']['enabled'] == 1){
		wp_schedule_event(strtotime($options['cron']['time']['hour'].':'.$options['cron']['time']['minute'].':00'), 'daily', 'azrcrv_rr_cron');
	}
	
}

/**
 * Clear Cron hourly check for widget announcements.
 *
 * @since 1.0.0
 *
 */
function clear_cron(){
	
	wp_clear_scheduled_hook("azrcrv_rr_cron");
	
}

/**
 * Register admin styles.
 *
 * @since 1.0.0
 *
 */
function register_admin_styles() {
    wp_register_style('azrcrv-rr-admin-styles', plugins_url('assets/css/admin.css', __FILE__));
}

/**
 * Enqueue admin styles.
 *
 * @since 1.0.0
 *
 */
function enqueue_admin_styles() {
	if (isset($_GET['page']) AND $_GET['page'] == 'azrcrv-rr'){
		wp_enqueue_style('azrcrv-rr-admin-styles');
	}
}

/**
 * Register admin scripts.
 *
 * @since 1.0.0
 *
 */
function register_admin_scripts() {
    wp_register_script('azrcrv-rr-admin-jquery', plugins_url('assets/jquery/admin.js', __FILE__));

}

/**
 * Enqueue admin scripts.
 *
 * @since 1.0.0
 *
 */
function enqueue_admin_scripts() {
	if (isset($_GET['page']) AND $_GET['page'] == 'azrcrv-rr'){
		wp_enqueue_script('azrcrv-rr-admin-jquery');
	}
}
	
/**
 * Load language files.
 *
 * @since 1.0.0
 *
 */
function load_languages() {
    $plugin_rel_path = basename(dirname(__FILE__)).'/languages';
    load_plugin_textdomain('rr', false, $plugin_rel_path);
}

/**
 * Get options including defaults.
 *
 * @since 1.0.0
 *
 */
function get_option_with_defaults($option_name){
 
	$defaults = array(
						'months-old' => 6,
						'cron' => array(
											'enabled' => 0,
											'time' => array(
																'hour' => 4,
																'minute' => 0,
															),
										),
						'post-types' => array(
										),
					);

	$options = get_option($option_name, $defaults);

	$options = recursive_parse_args($options, $defaults);

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 *
 * @since 1.0.0
 *
 */
function recursive_parse_args( $args, $defaults ) {
	$new_args = (array) $defaults;

	foreach ( $args as $key => $value ) {
		if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
			$new_args[ $key ] = recursive_parse_args( $value, $new_args[ $key ] );
		}
		else {
			$new_args[ $key ] = $value;
		}
	}

	return $new_args;
}

/**
 * Add action link on plugins page.
 *
 * @since 1.0.0
 *
 */
function add_plugin_action_link($links, $file){
	static $this_plugin;

	if (!$this_plugin){
		$this_plugin = plugin_basename(__FILE__);
	}

	if ($file == $this_plugin){
		$settings_link = '<a href="'.admin_url('admin.php?page=azrcrv-rr').'"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />'.esc_html__('Settings' ,'remove-revisions').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

/**
 * Custom plugin image path.
 *
 * @since 1.0.0
 *
 */
function custom_image_path($path){
    if (strpos($path, 'azrcrv-remove-revisions') !== false){
        $path = plugin_dir_path(__FILE__).'assets/pluginimages';
    }
    return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.0.0
 *
 */
function custom_image_url($url){
    if (strpos($url, 'azrcrv-remove-revisions') !== false){
        $url = plugin_dir_url(__FILE__).'assets/pluginimages';
    }
    return $url;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 *
 */
function create_admin_menu(){
	//global $admin_page_hooks;
	
	add_submenu_page("azrcrv-plugin-menu"
						,esc_html__("Remove Revisions Settings", "remove-revisions")
						,esc_html__("Remove Revisions", "remove-revisions")
						,'manage_options'
						,'azrcrv-rr'
						,__NAMESPACE__.'\\display_options');
}

/**
 * Load admin css.
 *
 * @since 1.0.0
 *
 */
function load_admin_style(){
    wp_register_style('rr-css', plugins_url('assets/css/admin.css', __FILE__), false, '1.0.0');
    wp_enqueue_style( 'rr-css' );
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 *
 */
function display_options(){
	if (!current_user_can('manage_options')){
        wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'remove-revisions'));
    }
	
	global $wpdb;
	
	// Retrieve plugin configuration options from database
	$options = get_option_with_defaults('azrcrv-rr');
	
	$types = get_option('azrcrv-rr-types');
	if (is_array($types)){ ksort($types); }
	
	
	echo '<div id="azrcrv-rr-general" class="wrap">';
	
		?>
		<h1>
			<?php
				echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="'.plugins_url('/pluginmenu/images/logo.svg', __FILE__).'" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
				esc_html_e(get_admin_page_title());
			?>
		</h1>
		<?php 
			
			if(isset($_GET['settings-updated'])){
				echo '<div class="notice notice-success is-dismissible">
					<p><strong>'.__('Settings have been saved.', 'remove-revisions').'</strong></p>
				</div>';
			}
		
		$tab_1 = '<table class="form-table">
		
								<tr>
									<th scope="row">
										
											'.esc_html__('Age to remove' , 'remove-revisions').'
										
									</th>
								
									<td>
										
										<input name="months-old" type="number" min="1" id="months-old" value="'.sanitize_text_field($options['months-old']).'" class="small-text" />
										
										<p class="description">
											'.esc_html__('Revisions older than this number of months will be removed.', 'remove-revisions').'
										</label>
									
									</td>
				
								</tr>
								
								<tr>
									<th scope="row" colspan=2>
										
											'.__('Select the post types which are to have their revisions removed.', 'remove-revisions').'
										
									</th>
									
								</tr>';
										
		$post_types = get_post_types([], 'objects');
		foreach($post_types as $post_type){
			if (isset($options['post-types'][$post_type->name])){
				$current_setting = $options['post-types'][$post_type->name];
			}else{
				$current_setting = 0;
			}
			$tab_1 .= '<tr>
							<th scope="row">
								
									&nbsp;
								
							</th>
						
							<td>
							
								<input name="post_types['.$post_type->name.']" type="checkbox" id="post_types['.$post_type->name.']" value="1" '.checked('1', $current_setting, false).' />
								<label for="post_types['.$post_type->name.']">
									'.$post_type->labels->name.'
								</label>
							
							</td>
		
						</tr>';
			
		}		
		
		$tab_1 .= '</table>';
		
		$tab_2 = '<table class="form-table">
								
								<tr>
								
									<th scope="row">
									
										<label for="enable-cron">
										
											'.__('Enable Cron', 'remove-revisions').'
											
										</label>
									</th>
										
									<td>
									
										<input name="enable-cron" type="checkbox" id="enable-cron" value="1" '.checked('1', $options['cron']['enabled'], false).' />
										<label for="enable-cron">
											'.__('Cron will run daily at the time selected below.', 'remove-revisions').'
										</label>
										
									</td>
									
								</tr>
						
								<tr>
									<th scope="row">
									
										'.esc_html__('Time', 'from-twitter').'
										
									</th>
									
									<td>
									
										<input type="number" min=0 max=23 step=1 name="cron-time-hour" style="width: 50px; " value="'.substr('0'.$options['cron']['time']['hour'], -2).'" onchange="if(parseInt(this.value,10)<10)this.value=\'0\'+this.value;" />:<input type="number" min=0 max=59 step=1 name="cron-time-minute" style="width: 50px; " value="'.substr('0'.$options['cron']['time']['minute'], -2).'" onchange="if(parseInt(this.value,10)<10)this.value=\'0\'+this.value;" />
										
									</td>
									
								</tr>
								
							</table>';
							
			$tab_1_label = esc_html__('Post Types', 'remove-revisions');
			$tab_2_label = esc_html__('Cron', 'remove-revisions');
		?>
		<form method="post" action="admin-post.php">
			<fieldset>
						
				<input type="hidden" name="action" value="azrcrv_rr_save_options" />
				<input name="page_options" type="hidden" value="enable-cron" />
				
				<?php
					//<!-- Adding security through hidden referrer field -->
					wp_nonce_field('azrcrv-rr', 'azrcrv-rr-nonce');
				?>
				
				<div id="tabs" class="ui-tabs">
					<ul class="ui-tabs-nav ui-widget-header" role="tablist">
						<li class="ui-state-default ui-state-active" aria-controls="tab-panel-1" aria-labelledby="tab-1" aria-selected="true" aria-expanded="true" role="tab">
							<a id="tab-1" class="ui-tabs-anchor" href="#tab-panel-1"><?php echo $tab_1_label; ?></a>
						</li>
						<li class="ui-state-default" aria-controls="tab-panel-2" aria-labelledby="tab-2" aria-selected="false" aria-expanded="false" role="tab">
							<a id="tab-2" class="ui-tabs-anchor" href="#tab-panel-2"><?php echo $tab_2_label; ?></a>
						</li>
					</ul>
					<div id="tab-panel-1" class="ui-tabs-scroll" role="tabpanel" aria-hidden="false">
						<?php echo $tab_1; ?>
					</div>
					<div id="tab-panel-2" class="ui-tabs-scroll ui-tabs-hidden" role="tabpanel" aria-hidden="true">
						<?php echo $tab_2; ?>
					</div>
				</div>
			</fieldset>
			
			<input type="submit" name="btn_save" value="<?php esc_html_e('Save Settings', 'remove-revisions'); ?>" class="button-primary"/>
			<input type="submit" name="btn_save_remove" value="<?php esc_html_e('Save Settings & Remove Revisions', 'remove-revisions'); ?>" class="button-secondary"/>
		</form>
	</div>
	<?php
	
}

/**
 * Save settings.
 *
 * @since 1.0.0
 *
 */
function save_options(){
	// Check that user has proper security level
	if (!current_user_can('manage_options')){
		wp_die(esc_html__('You do not have permissions to perform this action', 'remove-revisions'));
	}
	
	// Check that nonce field created in configuration form is present
	if (! empty($_POST) && check_admin_referer('azrcrv-rr', 'azrcrv-rr-nonce')){
	
		// Retrieve original plugin options array
		$options = get_option('azrcrv-rr');
		
		$options['months-old'] = (int) $_POST['months-old'];
		
		//post types
		$post_types = isset( $_POST['post_types'] ) ? (array) $_POST['post_types'] : array();
		$post_types = array_map( 'sanitize_text_field', $post_types );
		$options['post-types'] = $post_types;
		
		//cron enabled
		if (isset($_POST['enable-cron'])){
			$options['cron']['enabled'] = 1;
		}else{
			$options['cron']['enabled'] = 0;
		}
		//cron time hour
		$options['cron']['time']['hour'] = sanitize_text_field(intval($_POST['cron-time-hour']));
		//cron time minute
		$options['cron']['time']['minute'] = sanitize_text_field(intval($_POST['cron-time-minute']));
		
		// Store updated options array to database
		update_option('azrcrv-rr', $options);
		
		/*
		* Remove scheduled cron events
		*/
		wp_clear_scheduled_hook("azrcrv_rr_cron");
		
		/*
		* Add scheduled cron event
		*/
		if ($options['cron']['enabled'] == 1){
					wp_schedule_event(strtotime(substr('0'.$options['cron']['time']['hour'], -2).':'.substr('0'.$options['cron']['time']['minute'], -2).':00'), 'daily', 'azrcrv_rr_cron');
		}
		
		if (isset($_POST['btn_save_remove'])) {
			execute_cron();
		}
		
		// Redirect the page to the configuration form that was processed
		wp_redirect(add_query_arg('page', 'azrcrv-rr&settings-updated', admin_url('admin.php')));
		exit;
	}
}

/**
 * Execute cron.
 *
 * @since 1.0.0
 *
 */
function execute_cron(){
	global $wpdb;
	
	$options = get_option_with_defaults('azrcrv-rr');
	
	$post_types = $options['post-types'];
	$in_post_types = array();
	foreach($post_types as $post_type => $value){
		$in_post_types[] = esc_attr($post_type);
	}
	$in_post_types = array_map(function($post_type) {
								return "'" . esc_sql($post_type) . "'";
							}, $in_post_types);
	$in_post_types = implode(',', $in_post_types);
	
	$sql = "SELECT 
				post.ID 
			FROM 
				$wpdb->posts AS post 
			INNER JOIN 
				$wpdb->posts AS parent 
					ON 
						parent.ID = post.post_parent 
			WHERE 
				post.post_type = 'revision' 
			AND 
				parent.post_type IN ($in_post_types) 
			AND 
				post.post_date <= (now() - interval %d month)";
	
	$prepared_sql = $wpdb->prepare($sql, esc_sql($options['months-old']));
	
	$post_ids = $wpdb->get_col($prepared_sql);
	if ($post_ids) {
		foreach ($post_ids as $post_id){
			wp_delete_post_revision((int) $post_id);
		}
	}
	
}