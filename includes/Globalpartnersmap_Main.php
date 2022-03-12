<?php
if (!defined('ABSPATH')) {
	die('-1');
}
if (!class_exists('Globalpartnersmap_Main')) {
	/**
	 * Plugin Main Class
	 */
	class Globalpartnersmap_Main
	{
		public $plugin_file;
		public $plugin_dir;
		public $plugin_path;
		public $plugin_url;

		/**
		 * Static Singleton Holder
		 * @var self
		 */
		protected static $instance;

		/**
		 * Get (and instantiate, if necessary) the instance of the class
		 *
		 * @return self
		 */
		public static function instance()
		{
			if (!self::$instance) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function __construct()
		{
			$this->plugin_file = Globalpartnersmap_PLUGIN_FILE;
			$this->plugin_path = trailingslashit(dirname($this->plugin_file));
			$this->plugin_dir  = trailingslashit(basename($this->plugin_path));
			$this->plugin_url  = str_replace(basename($this->plugin_file), '', plugins_url(basename($this->plugin_file), $this->plugin_file));
			add_action('plugins_loaded', array($this, 'plugins_loaded'), 1);
			add_filter('plugin_action_links', array($this, 'fn_add_settings_link_plugin'), 10, 4);
			add_filter('network_admin_plugin_action_links', array($this, 'fn_add_settings_link_plugin'), 10, 4);
			add_action('admin_menu', array($this, 'fn_Globalpartnersmap_admin_menu_callback'));
			/*	add_action('admin_enqueue_scripts', array($this, 'fn_pVerify_enqueue_admin_scripts'));*/
			add_action('wp_enqueue_scripts', array($this, 'fn_Globalpartnersmap_enqueue_front_scripts'));
			add_action('init', array($this, 'fn_Globalpartnersmap_add_shortcode'));
			add_action('wp_ajax_nopriv_fn_save_partner_form', array($this, 'fn_save_partner_form'));
			add_action('wp_ajax_fn_save_partner_form', array($this, 'fn_save_partner_form'));
			add_action('admin_enqueue_scripts', array($this, 'plugin_scripts'));
			add_action('wp_ajax_fn_approve_btn_data', array($this, 'fn_approve_btn_data'));
			//add_action('wp_ajax_submit_verifyKeysform', array($this,'fn_pVerify_submit_verifyKeysform'));
			add_action('wp_ajax_fn_get_map_data', array($this, 'fn_get_map_data'));
			add_action('wp_ajax_nopriv_fn_get_map_data', array($this, 'fn_get_map_data'));
			add_action('admin_init', array($this, 'remove_theme_submenus'));
			// Onclick Process map data 
			add_action('wp_ajax_fn_map_process', array($this, 'fn_map_process'));
			add_action('wp_ajax_nopriv_fn_map_process', array($this, 'fn_map_process'));
			add_action('wp_ajax_fn_Edit_btn_data', array($this, 'fn_Edit_btn_data'));
			add_action('wp_ajax_nopriv_fn_Edit_btn_data', array($this, 'fn_Edit_btn_data'));
			add_action('wp_ajax_fn_popup_btn_data', array($this, 'fn_popup_btn_data'));
			add_action('wp_ajax_nopriv_fn_popup_btn_data', array($this, 'fn_popup_btn_data'));
			add_action('wp_ajax_fn_Delete_btn_data', array($this, 'fn_Delete_btn_data'));
			add_action('wp_ajax_nopriv_fn_Delete_btn_data', array($this, 'fn_Delete_btn_data'));
			add_action('wp_ajax_fn_unapproved_btn_data', array($this, 'fn_unapproved_btn_data'));
			add_action('wp_ajax_nopriv_fn_unapproved_btn_data', array($this, 'fn_unapproved_btn_data'));
		}
		/**
		 * plugin activation callback
		 * @see register_deactivation_hook()
		 *
		 * @param bool $network_deactivating
		 */
		public static function activate()
		{
			$plugin_path = dirname(Globalpartnersmap_PLUGIN_FILE);

			require_once $plugin_path . '/includes/Globalpartnersmap-db-config.php';
			$db_obj = new Globalpartnersmap_DB_Config();
			$db_obj->fn_create_Globalpartnersmap_tables();
		}
		/**
		 * plugin deactivation callback
		 * @see register_deactivation_hook()
		 *
		 * @param bool $network_deactivating
		 */
		public static function deactivate($network_deactivating)
		{
		}
		/**
		 * plugin deactivation callback
		 * @see register_uninstall_hook()
		 *
		 * @param bool $network_uninstalling
		 */
		public static function uninstall()
		{
			global $table_prefix, $wpdb;
			$plugin_path = dirname(Globalpartnersmap_PLUGIN_FILE);
			require_once $plugin_path . '/includes/Globalpartnersmap-db-config.php';
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			$Globalpartnersmap_pl_table = $table_prefix . "$tblname";
			$wpdb->query("DROP TABLE IF EXISTS $Globalpartnersmap_pl_table");
		}
		public function plugins_loaded()
		{
			$this->loadLibraries();
		}
		/**
		 * Load all the required library files.
		 */
		protected function loadLibraries()
		{
			require_once $this->plugin_path . 'includes/Globalpartnersmap-db-config.php';
		}
		public function fn_add_settings_link_plugin($actions, $plugin_file, $plugin_data, $context)
		{
			if (!array_key_exists('settings', $actions) && $plugin_file == "WordpressPlugin-main/Globalpartnersmap_Main.php" && current_user_can('manage_options')) {

				$url = admin_url("admin.php?page=Globalpartnersmap");
				$actions['settings'] = sprintf('<a href="%s">%s</a>', $url, __('Settings', 'Globalpartnersmap'));
			}
			return $actions;
		}
		public function fn_Globalpartnersmap_admin_menu_callback()
		{
			add_menu_page(
				__('Globalpartnersmap', 'Globalpartnersmap'),
				'Globalpartnersmap',
				'manage_options',
				'Globalpartnersmap',
				array($this, 'fn_Globalpartnersmap_admin_menu_page_callback'),
				'dashicons-admin-generic',
				25
			);
			add_submenu_page('Globalpartnersmap', 'Approved Parteners', 'Approved Partners', 'manage_options', 'approvedpartners', array($this, 'fn_dashboard_approved_partners'));
		}
		function fn_Globalpartnersmap_admin_menu_page_callback()
		{
			require_once($this->plugin_path . 'includes/view/admin/all_partners.php');
		}
		function fn_dashboard_approved_partners()
		{
			require_once($this->plugin_path . 'includes/view/admin/approved_partners.php');
		}
		public function fn_Globalpartnersmap_enqueue_front_scripts()
		{
			if (is_admin()) {
				wp_enqueue_script('Globalpartnersmap-plugin-back-script', $this->plugin_url . 'assets/js/admin/jquery.min.js');
				wp_enqueue_script('Globalpartnersmap-plugin-back-js', $this->plugin_url . '/assets/js/admin/admin-backend.js');

				$script_params = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'site_url' => site_url()
				);
				wp_localize_script('Globalpartnersmap-plugin-back-js', 'scriptParams', $script_params);
			}
			wp_enqueue_style('Globalpartnersmap-front-css', $this->plugin_url . "assets/css/front_style.css");
			wp_enqueue_script('theme-script_front', $this->plugin_url . 'assets/js/front/jquery.min.js');
			wp_enqueue_script('Globalpartnersmap-plugin-js', $this->plugin_url . 'assets/js/front/map.js', array(), '1.0.0', true);

			$script_params = array(
				'ajaxurl' => admin_url('admin-ajax.php')
			);
			wp_localize_script('Globalpartnersmap-plugin-js', 'scriptParams', $script_params);
		}
		public function plugin_scripts()
		{
			if (is_admin()) {
				wp_enqueue_script('Globalpartnersmap-plugin-back-script', $this->plugin_url . 'assets/js/admin/jquery.min.js');
				wp_enqueue_script('Globalpartnersmap-plugin-back-js', $this->plugin_url . '/assets/js/admin/admin-backend.js');
				wp_enqueue_script('sweet_alert', $this->plugin_url . '/assets/js/admin/SweetAlert.min.js');
				wp_enqueue_style('SweetAlert_css', $this->plugin_url . "/assets/css/SweetAlert.css");
				$script_params = array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'site_url' => site_url()
				);
				wp_localize_script('Globalpartnersmap-plugin-back-js', 'scriptParams', $script_params);
			}
		}
		public function fn_Globalpartnersmap_add_shortcode()
		{
			add_shortcode('Global_Partners_Form', array($this, 'fn_global_partners_Form_short'));
			add_shortcode('Global_Partners_map', array($this, 'fn_global_map'));
			add_shortcode('Global_Partners_dataprocess', array($this, 'dataprocess'));
			add_shortcode('Global_Partners_fetch', array($this, 'fn_global_partners_Fetch_short'));
			add_shortcode('Single_Global_Partners', array($this, 'fn_single_global_partners'));
		}
		public function fn_global_partners_Form_short()
		{
			require_once($this->plugin_path . 'includes/view/shortcodes/form.php');
		}
		public function fn_global_partners_Fetch_short()
		{
			require_once($this->plugin_path . 'includes/view/shortcodes/fetch.php');
		}
		function remove_theme_submenus()
		{
			global $submenu;
			unset($submenu['Globalpartnersmap']['0']['1']);
		}
		public function fn_global_map()
		{
			ob_start();
			require_once($this->plugin_path . 'includes/view/shortcodes/map.php');
			return ob_get_clean();
		}
		public function fn_single_global_partners()
		{
			ob_start();
			require_once($this->plugin_path . 'includes/view/shortcodes/single_global_partners.php');
			return ob_get_clean();
		}
		public function fn_save_partner_form()
		{
			$count =0;
			$name = sanitize_text_field($_POST['name']);
			$email = sanitize_text_field($_POST['email']);
			$phone_number = sanitize_text_field($_POST['phone_number']);
			$address = sanitize_text_field($_POST['address']);
			$country = sanitize_text_field($_POST['country']);
			$response = array('status' => 'failed', 'msg' => 'Something went wrong, please try again after some time.');
			if (empty($name) || empty($email)) {
				echo json_encode($response);
				exit();
			}
			global $wpdb;
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			// $inserted = $wpdb->query("INSERT INTO {$wpdb->prefix}$tblname (Name,Email,Phone_number,Adress,Country ) VALUES ('$name', '$email','$phone_number','$address','$country') WHERE NOT EXISTS (SELECT * FROM {$wpdb->prefix}$tblname WHERE Email ='$email' AND Phone_number='$phone_number');");
			$inserted = $wpdb->query("INSERT INTO {$wpdb->prefix}$tblname (Name,Email,Phone_number,Adress,Country ) SELECT * FROM (SELECT '$name', '$email','$phone_number','$address','$country') AS tmp WHERE NOT EXISTS ( SELECT Name FROM {$wpdb->prefix}$tblname WHERE Name = '$name' ) LIMIT 1;");
			echo $wpdb->last_query;
			if ($inserted !== false) {
				wp_send_json_success(array('message' => __('Details saved successfully.', 'Globalpartnersmap')));
			}
			wp_send_json_error(array('message' => 'Something went wrong, please try again after some time.'));
		}
		public function fn_map_process()
		{
			$cookie_name = "Country";
			$cookie_value = $_POST['Country'];
			setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day
			require_once($this->plugin_path . 'includes/view/shortcodes/single_global_partners.php');
			echo json_encode($_POST['Country']);
			exit();
		}
		public function fn_get_map_data()
		{
			$dbdata = array();
			global $wpdb;
			$result = $wpdb->get_results("SELECT Country, count(*) as NUM FROM wp_globalpartnersmap_pl GROUP BY Country ");
			foreach ($result as $print) {
				$dbdata[] = $print;
			}
			echo json_encode($dbdata);
			exit();
		}
		public function fn_approve_btn_data()
		{
			$id = $_POST['id'];
			global $wpdb;
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			$updated = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}$tblname SET Status='1'  WHERE id = $id"));
			echo $wpdb->last_query;
			die;
		}
		public function fn_Edit_btn_data()
		{
			echo "<pre>";
			print_r($_POST);
		}
		public function fn_popup_btn_data()
		{
			$db_id = $_POST['db_id'];
			echo $db_id;
			$id = $_POST['IDFR'];
			$Name = $_POST['fname'];
			$Email = $_POST['Email'];
			$Address = $_POST['phone_no'];
			$Phone = $_POST['Address'];
			$Country = $_POST['Country'];
			global $wpdb;
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			$updated = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}$tblname  SET `Phone_number` = '$Phone', `Adress` = '$Address', `Status` = b'0' WHERE `wp_globalpartnersmap_pl`.`id` = $db_id;"));
			echo $wpdb->last_query;
			exit;
		}
		public function fn_Delete_btn_data()
		{
			$delete_id = $_POST['delete_id'];
			global $wpdb;
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			$updated = $wpdb->query($wpdb->prepare("DELETE FROM  {$wpdb->prefix}$tblname  WHERE id=$delete_id;"));
			echo $wpdb->last_query;
			exit;
		}
		public function fn_unapproved_btn_data()
		{
			$unapproved = $_POST['unapproved'];
			echo $unapproved;
			global $wpdb;
			$db_obj = new Globalpartnersmap_DB_Config();
			$tblname = $db_obj->Globalpartnersmap_pl_table;
			$unapproved = $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}$tblname SET `Status` = b'0' WHERE  {$wpdb->prefix}$tblname.`id` = $unapproved;"));
			echo $wpdb->last_query;
			exit;
		}
	}
}
