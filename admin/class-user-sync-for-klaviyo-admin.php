<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://oakandbeech.com
 * @since      1.0.0
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    User_Sync_For_Klaviyo
 * @subpackage User_Sync_For_Klaviyo/admin
 * @author     Oak and Beech <info@oakandbeech.com>
 */
class User_Sync_For_Klaviyo_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	const ACCEPTED_SETTINGS = ['klaviyo_public_key', 'klaviyo_private_key', 'inject_klaviyo_script', 'activate_user_sync'];

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;

		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Sync_For_Klaviyo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Sync_For_Klaviyo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/user-sync-for-klaviyo-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in User_Sync_For_Klaviyo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The User_Sync_For_Klaviyo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/user-sync-for-klaviyo-admin.js', array('jquery'), $this->version, false);
		wp_localize_script(
			$this->plugin_name,
			'swk',
			array(
				'ajaxurl' => admin_url('admin-ajax.php'),
				'nonce' => wp_create_nonce('ajax-nonce')
			)
		);
	}

	public function register_admin_menu()
	{

		add_menu_page('User Sync For Klaviyo Settings', 'User Sync For Klaviyo', 'manage_options', 'user_sync_for_klaviyo_settings.php', array($this, 'admin_menu_settings'), 'dashicons-update', 70);
	}

	public function admin_menu_settings()
	{
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/user-sync-for-klaviyo-admin-display.php';
	}

	public function plugin_action_links($links)
	{
		$links = array_merge(array(
			'<a href="' . esc_url(admin_url('admin.php?page=user_sync_for_klaviyo_settings.php')) . '">Settings</a>'
		), $links);

		return $links;
	}

	public function settings_init()
	{
		/**
		 * Register the "settings group"
		 * The first param is the name of the group we hook into
		 * The second param is the option key settings will be stored under in the database
		 * Settings will be store as JSON here
		 */
		register_setting(
			'user_sync_for_klaviyo_settings_group',
			USER_SYNC_FOR_KLAVIYO_SETTINGS,
			array($this, 'process_settings')
		);
		/**
		 * Each setting needs a "section" to be part of, this is the general setting section
		 */
		add_settings_section(
			'user_sync_for_klaviyo_general_section',
			'General Settings',
			null,
			'user_sync_for_klaviyo_settings_group'
		);

		/**
		 * Register the setting field and assign it to the relevant group and section
		 */

		add_settings_field(
			'activate_user_sync',
			'Enable User Sync',
			array($this, 'activate_user_sync_render'),
			'user_sync_for_klaviyo_settings_group',
			'user_sync_for_klaviyo_general_section'
		);

		add_settings_field(
			'klaviyo_private_key',
			'Klaviyo Private Key',
			array($this, 'klaviyo_private_key_render'),
			'user_sync_for_klaviyo_settings_group',
			'user_sync_for_klaviyo_general_section'
		);

		add_settings_field(
			'klaviyo_public_key',
			'Klaviyo Public Key',
			array($this, 'klaviyo_public_key_render'),
			'user_sync_for_klaviyo_settings_group',
			'user_sync_for_klaviyo_general_section'
		);

		add_settings_field(
			'inject_klaviyo_script',
			'Add Klaviyo Script To Footer',
			array($this, 'inject_klaviyo_script_render'),
			'user_sync_for_klaviyo_settings_group',
			'user_sync_for_klaviyo_general_section'
		);
	}

	function activate_user_sync_render()
	{
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
?>
		<input type="radio" name="user_sync_for_klaviyo_settings[activate_user_sync]" id="user_sync_for_klaviyo_on" value="on" <?php echo (isset($options['activate_user_sync']) && esc_attr($options['activate_user_sync']) == 'on') ? "checked=checked" : ""; ?>><label for="user_sync_for_klaviyo_on">On</label>
		<input type="radio" name="user_sync_for_klaviyo_settings[activate_user_sync]" id="user_sync_for_klaviyo_off" value="off" <?php echo (!isset($options['activate_user_sync']) || esc_attr($options['activate_user_sync']) == 'off') ? "checked=checked" : ""; ?>><label for="user_sync_for_klaviyo_off">Off</label>
		<p><i class="swk-help-description">Enable automatic syncing of users from Wordpress to Klaviyo</i></p>
	<?php
	}

	function klaviyo_public_key_render()
	{
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
	?>
		<input type='text' name='user_sync_for_klaviyo_settings[klaviyo_public_key]' value='<?php echo (isset($options['klaviyo_public_key'])) ? esc_attr($options['klaviyo_public_key']) : ""; ?>'>
		<i class="swk-help-description"><a href="https://help.klaviyo.com/hc/en-us/articles/115005062267-How-to-manage-your-account-s-API-keys" target="_blank">Find your public key <span class="dashicons dashicons-external"></span></a></i>
		<?php
	}

	function klaviyo_private_key_render()
	{
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
		if (isset($options['klaviyo_private_key'])) { ?>
			<p style="position:relative">
				<input type='password' name='user_sync_for_klaviyo_settings[klaviyo_private_key]' value='<?php echo (isset($options['klaviyo_private_key'])) ? esc_attr($options['klaviyo_private_key']) : ""; ?>'>
				<span class="dashicons dashicons-visibility key-reveal"></span>
			</p>

		<?php } else { ?>
			<input type='text' name='user_sync_for_klaviyo_settings[klaviyo_private_key]'>
			<i class="swk-help-description"><a href="https://help.klaviyo.com/hc/en-us/articles/7423954176283" target="_blank">Create a private key <span class="dashicons dashicons-external"></span></a></i>
		<?php }
		?>

	<?php
	}

	function inject_klaviyo_script_render()
	{
		$options = get_option(USER_SYNC_FOR_KLAVIYO_SETTINGS);
	?>
		<input type="radio" name="user_sync_for_klaviyo_settings[inject_klaviyo_script]" id="inject_klaviyo_script_on" value="on" <?php echo (isset($options['inject_klaviyo_script']) && esc_attr($options['inject_klaviyo_script']) == 'on') ? "checked=checked" : ""; ?>><label for="inject_klaviyo_script_on">On</label>
		<input type="radio" name="user_sync_for_klaviyo_settings[inject_klaviyo_script]" id="inject_klaviyo_script_off" value="off" <?php echo (!isset($options['inject_klaviyo_script']) || esc_attr($options['inject_klaviyo_script']) == 'off') ? "checked=checked" : ""; ?>><label for="inject_klaviyo_script_off">Off</label>
		<p><i class="swk-help-description">Enable this to add the Klaviyo javascript to your website to enable signup forms and web tracking - <a href="https://developers.klaviyo.com/en/docs/javascript_api" target="_blank">more <span class="dashicons dashicons-external"></span></a></i></p>
<?php
	}

	function process_settings($inputs)
	{
		$sanitised_inputs = array();
		$errors = false;
		// make sure we only accept valid input settings as we have defined 
		foreach (self::ACCEPTED_SETTINGS as $setting) {
			if (array_key_exists($setting, $inputs)) {
				$sanitised_inputs[$setting] = $inputs[$setting];
			}
		}
		// validate Klaviyo public key length
		if (isset($inputs['klaviyo_public_key']) && strlen($inputs['klaviyo_public_key']) != 6) {
			if ($inputs['klaviyo_public_key'] == '') {
				add_settings_error(USER_SYNC_FOR_KLAVIYO_SETTINGS, "invalid", "You need to enter your Klaviyo Public key", 'error');
			} else {
				add_settings_error(USER_SYNC_FOR_KLAVIYO_SETTINGS, "invalid", "Your Klaviyo Public Key should be 6 characters long", 'error');
			}
			$errors = true;
		}

		if (isset($inputs['klaviyo_private_key']) && strlen($inputs['klaviyo_private_key']) < 12) {
			if ($inputs['klaviyo_private_key'] == '') {
				add_settings_error(USER_SYNC_FOR_KLAVIYO_SETTINGS, "invalid", "You need to enter your Klaviyo Private key", 'error');
			} else {
				add_settings_error(USER_SYNC_FOR_KLAVIYO_SETTINGS, "invalid", "Your Klaviyo Private Key should be longer", 'error');
			}
			$errors = true;
		}

		if (!$errors) {
			add_settings_error(USER_SYNC_FOR_KLAVIYO_SETTINGS, "success", "Settings updated!", "success");
		}
		return $sanitised_inputs;
	}

	function get_user_properties($user_data)
	{
		$properties = array(
			'email' => $user_data->user_email,
			'first_name' => $user_data->first_name,
			'last_name' => $user_data->last_name,
			'wordpress_user_id' => $user_data->ID,
			'wordpress_user_login' => $user_data->user_login,
			'wordpress_user_role' => $user_data->roles[0] ?? "No role set",
			'wordpress_user_registered' => $user_data->user_registered,
			'wordpress_user_last_updated' => date("Y-m-d H:i:s")
		);
		return $properties;
	}

	function get_user_event_properties($user_data)
	{
		$properties = array(
			'updated_wordpress_user_role' => $user_data->roles[0] ?? "No role set",
			'updated_first_name' => $user_data->first_name,
			'updated_last_name' => $user_data->last_name,
		);
		return $properties;
	}

	function create_klaviyo_profile($user_id, $historical = false)
	{
		try {
			$this->create_klaviyo_event($user_id, "WordPress - Created User", $historical);
		} catch (Exception $e) {
			error_log("User Sync For Klaviyo : The request to Create profile in Klaviyo failed Userd ID" . (int) $user_id);
			error_log(print_r(esc_html($e->getMessage()), true));
		}
	}

	function update_klaviyo_profile($user_id)
	{
		try {
			$this->create_klaviyo_event($user_id, "WordPress - Updated User");
		} catch (Exception $e) {
			error_log("User Sync For Klaviyo : The request to Update Klaviyo failed Userd ID" . (int) $user_id);
			error_log(print_r(esc_html($e->getMessage()), true));
		}
	}

	function create_klaviyo_event($user_id, $event_name = "WordPress - Updated User", $historical = false)
	{
		$user_data = get_userdata($user_id);
		$user_properties = $this->get_user_properties($user_data);
		$user_event_properties = $this->get_user_event_properties($user_data);
		$klaviyo_url = 'https://a.klaviyo.com/api/events/';
		$data = array(
			'data' => array(
				'type' => 'event',
				'attributes' => array(
					'profile' => $user_properties,
					'metric' => array(
						'name' => $event_name
					),
					'properties' => $user_event_properties
				)
			)
		);
		// if the $historical flag is true, that means this was a bulk job to sync old profiles, so we can added a timestamp so it looks accurate in Klaviyo
		if ($historical) {
			$created_datetime = DateTime::createFromFormat('Y-m-d H:i:s', $user_properties['wordpress_user_registered']);
			$formated_created_datetime = $created_datetime->format('c');
			$data['data']['attributes']['time'] = $formated_created_datetime;
			// we also unset the "last updated" time because that's not actually correct here
			unset($data['data']['attributes']['profile']['wordpress_user_last_updated']);
		}
		// avoiding errors in case some wordpress users have no email set
		if (!is_email($user_properties['email'])) {
			error_log("User Sync For Klaviyo : Skipped {$user_id} due to missing or invalid email address");
			return;
		} else {
			$this->send_post_request($klaviyo_url, $data);
		}
	}

	function build_klaviyo_headers()
	{
		// @TODO validate this again
		$private_key = get_option('user_sync_for_klaviyo_settings')['klaviyo_private_key'];
		$headers = array(
			'Authorization' => 'Klaviyo-API-Key ' . $private_key,
			'accept' => 'application/json',
			'content-type' => 'application/json',
			'revision' => '2023-02-22'
		);
		return $headers;
	}

	function send_post_request($url, $data)
	{
		// Set the API endpoint URL
		$api_url = $url;
		// Prepare the request arguments
		$args = array(
			'headers' => $this->build_klaviyo_headers(),
			'body' => wp_json_encode($data)
		);

		// // Send the POST request
		$response = wp_remote_post($api_url, $args);
		// Check for errors
		if (is_wp_error($response)) {
			// Handle error case
			error_log("User Sync For Klaviyo : The request to Klaviyo failed - body below");
			error_log(print_r(esc_html($data), true));
			throw new Exception($response->error_message . ":" . $response->error_message);
		} else {
			if (wp_remote_retrieve_response_code($response) != 202) {
				error_log("User Sync For Klaviyo : The request to Klaviyo failed - body below");
				error_log(print_r(esc_html($data)), true);
				$message = json_decode($response['body']);

				throw new Exception("Error code: " . wp_remote_retrieve_response_code($response) . " Message:" . $message->errors[0]->title);
			}
			// Return the response
			return $response['body'];
		}
	}

	function ajax_sync_all_users()
	{
		if (!wp_verify_nonce($_POST['nonce'], 'ajax-nonce')) {
			echo json_encode(array('status' => 'error', 'sync_status' => 'error', 'error_message' => esc_html("Missing Nonce token!")));
			wp_die();
		}
		$args = array(
			'fields' => sanitize_text_field($_POST['fields']),
			'count_total' => true,
			'paged' => sanitize_text_field($_POST['paged']),
			'number' => sanitize_text_field($_POST['number'])
		);
		$paged = sanitize_text_field($_POST['paged']);
		$users = get_users($args);
		foreach ($users as $user_id) {
			try {
				$this->create_klaviyo_profile($user_id, true);
			} catch (Exception $e) {
				echo json_encode(array('status' => 'error', 'sync_status' => 'error', 'error_message' => esc_html($e->getMessage())));
				wp_die();
			}
		}
		$status = 'processing';
		$paged++;
		if (count($users) < sanitize_text_field($_POST['number'])) {
			$status = 'finished';
		}
		echo json_encode(array('status' => 'success', 'users' => $users, 'sync_status' => esc_html($status), 'next_paged' => (int) $paged));
		wp_die();
	}
}
