<?php

/**
 * Plugin Name:    WP Inventory
 * Plugin URI:    http://www.wpinventory.com
 * Description:    Manage and display your products just like a shopping cart, but without the cart.
 * Version:        1.4.3
 * Author:        WP Inventory Manager
 * Author URI:    http://www.wpinventory.com/
 * Text Domain:    wpinventory
 *
 * ------------------------------------------------------------------------
 * Copyright 2009-2016 WP Inventory Manager, LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once "includes/wpinventory.class.php";

/**
 * This is the class that takes care of all the WordPress hooks and actions.
 * The real management takes place in the WPInventory Class
 * @author WP Inventory Manager
 */
class WPInventoryInit extends WPIMCore {

	public static function initialize() {


		if ( 0 < version_compare( self::MIN_PHP_VERSION, phpversion() ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'min_php_version' ) );

			return;
		}

		self::require_files();

		self::$url  = plugins_url( '', __FILE__ );
		self::$path = plugin_dir_path( __FILE__ );

		self::plugins_loaded();
		self::add_actions();

		// Dependency Injection.  Singleton pattern.
		self::$config = WPIMConfig::getInstance();
		self::$api    = WPIMAPI::getInstance();

		do_action( 'wpim_core_loaded' );
	}

	private static function require_files() {
		require_once "includes/wpinventory.config.class.php";
		require_once "includes/wpinventory.api.class.php";
		require_once "includes/wpinventory.db.class.php";
		require_once "includes/wpinventory.item.class.php";
		require_once "includes/wpinventory.category.class.php";
		require_once "includes/wpinventory.status.class.php";
		require_once "includes/wpinventory.label.class.php";
		require_once "includes/wpinventory.status.class.php";
		require_once "includes/wpinventory.admin.class.php";
		require_once "includes/wpinventory.template.class.php";
		require_once "includes/wpinventory.shortcode.class.php";
		require_once "includes/wpinventory.widgets.class.php";
		require_once "includes/wpinventory.loop.class.php";
		require_once "includes/wpinventory.search.class.php";
		require_once "includes/wpinventory.support.class.php";
		require_once "includes/wpinventory.functions.php";
		require_once "includes/wpinventory.updater.php";
	}

	/**
	 * Set up all the wordpress hooks
	 */
	private static function add_actions() {
		$actions = array(
			'init',
			'widgets_init',
			'admin_notices',
			'admin_init',
			'admin_menu',
			'admin_enqueue_scripts',
			'wp_enqueue_scripts',
			'admin_print_footer_scripts',
		);

		foreach ( $actions as $action ) {
			if ( method_exists( __CLASS__, $action ) ) {
				add_action( $action, array( __CLASS__, $action ) );
			}
		}

		add_filter( 'redirect_canonical', array( __CLASS__, 'disable_canonical_redirect_for_front_page' ) );
		add_action( 'wp_ajax_wpim_notice_handler', array( __CLASS__, 'ajax_notice_handler' ) );
	}

	/**
	 * WordPress plugins_loaded action callback.  We use this to initialize the loading of any WP Inventory add-ons
	 */
	public static function plugins_loaded() {
		self::plugin_updater();
		do_action( 'wpim_load_add_ons' );
	}

	/**
	 * WordPress admin_init action callback function
	 */
	public static function init() {
		if ( ! session_id() ) {
			session_start();
		}
		add_shortcode( self::SHORTCODE, array( __CLASS__, 'shortcode' ) );
		// Enable internationalization
		if ( ! load_plugin_textdomain( 'wpinventory', FALSE, '/wp-content/languages/' ) ) {
			load_plugin_textdomain( 'wpinventory', FALSE, basename( dirname( __FILE__ ) ) . "/languages/" );
		}

		add_action( 'wp_ajax_wpim_send_support', array( self::SUPPORT_CLASS, 'ajax_send_support' ) );

		self::setup_seo_endpoint();

		self::$config->loadConfig();
	}

	/**
	 * WordPress widgets_init action callback function
	 */
	public static function widgets_init() {
		register_widget( 'WPInventory_Categories_Widget' );
		register_widget( 'WPInventory_Latest_Items_Widget' );
	}

	/**
	 * WordPress admin_notices action callback
	 */
	public static function admin_notices() {
		if ( ! self::is_wpinventory_page() && self::notice_dismissed( 'core-license' ) ) {
			return;
		}

		if ( ! self::is_honest_user() && ! isset( $_POST['license_key'] ) ) {
			$dismissible = ( self::is_wpinventory_page() ) ? '' : ' is-dismissible';
			echo '<div class="notice notice-error notice-wpinventory' . $dismissible . '" data-notice="core-license">';
			echo '<p>';
			echo self::__( 'WP Inventory Manager is unlicensed.  Get automatic updates and support by getting a license.' );
			echo ' <a href="admin.php?page=wpim_manage_settings">' . self::__( 'Enter your license key now.' ) . '</a>';
			echo '</p>';
			echo '</div>';
		}
	}

	/**
	 * WordPress admin_init action callback function
	 */
	public static function admin_init() {
		register_setting( self::SETTINGS_GROUP, self::SETTINGS );
		self::$options = get_option( self::SETTINGS );
		wp_enqueue_style( 'inventory-admin-style', self::$url . '/css/style-admin.css' );
	}

	/**
	 * WordPress admin_menu action callback function
	 */
	public static function admin_menu() {
		$lowest_role = self::$config->get( 'permissions_lowest_role' );
		add_menu_page( self::__( 'WP Inventory' ), self::__( 'WP Inventory' ), $lowest_role, self::MENU, array(
			__CLASS__,
			'instructions'
		), self::$url . '/images/admin-menu-icon.png' );
		self::add_submenu( 'Inventory Items', $lowest_role );
		self::add_submenu( 'Categories' );
		self::add_submenu( 'Labels' );
		self::add_submenu( 'Display' );
		self::add_submenu( 'Statuses' );
		do_action( 'wpim_admin_menu' );
		self::add_submenu( 'Add Ons' );
		self::add_submenu( 'Settings' );
		self::add_submenu( 'Support', $lowest_role, self::SUPPORT_CLASS );
		self::$pages = apply_filters( 'wpim_admin_pages', self::$pages );
	}

	/**
	 * Utility function to simplify adding submenus
	 *
	 * @param string $title
	 * @param string $role
	 * @param string $class
	 */
	private static function add_submenu( $title, $role = 'manage_options', $class = "" ) {
		if ( ! $class || ! class_exists( $class ) ) {
			$class = __CLASS__;
		}

		$slug = strtolower( str_replace( " ", "_", $title ) );
		switch ( strtolower( $title ) ) {
			case 'inventory items':
				$title = self::__( 'Inventory Items' );
				break;
			case 'categories':
				$title = self::__( 'Categories' );
				break;
			case 'labels':
				$title = self::__( 'Labels' );
				break;
			case 'display':
				$title = self::__( 'Display' );
				break;
			case 'settings':
				$title = self::__( 'Settings' );
				break;
			case 'statuses':
				$title = self::__( 'Statuses' );
				break;
			case 'add ons':
				$title = self::__( 'Add Ons' );
				break;
		}

		add_submenu_page( self::MENU, $title, $title, $role, 'wpim_manage_' . $slug, array( $class, 'admin_' . $slug ) );
		self::$pages[] = 'wpim_manage_' . $slug;
	}

	public static function admin_print_footer_scripts() {
		$themes = self::load_available_themes();
		?>
        <script>var wpinventory_themes = <?php echo json_encode( $themes ); ?>;
          // Support dismissable nags.  Required on all pages, not just WPIM pages.
          jQuery( function ( $ ) {
            $( document ).on( 'click', '.notice-wpinventory .notice-dismiss', function () {
              var type = $( this ).closest( '.notice-wpinventory' ).data( 'notice' );
              $.ajax( ajaxurl,
                {
                  type: 'POST',
                  data: {
                    action: 'wpim_notice_handler',
                    type: type,
                    nonce: '<?php echo wp_create_nonce( self::NONCE_ACTION ); ?>'
                  }
                } );
            } );

            if ( $( 'select.wpinventory_themes' ).length ) {
              $( 'select.wpinventory_themes' ).change(
                function () {
                  var theme_name = $( this ).val();
                  var screenshot;
                  if ( wpinventory_themes[ theme_name ] ) {
                    screenshot = wpinventory_themes[ theme_name ][ 'screenshot' ];
                  }
                  if ( typeof screenshot != 'undefined' ) {
                    $( '<img src="' + screenshot + '">' ).load(
                      function () {
                        $( '.theme_screenshot' ).empty().append( $( this ) );
                      }
                    )
                  }
                }
              ).trigger( 'change' );
            }
          } );</script>
		<?php
	}

	public static function shortcode( $args ) {
		self::$shortcode = WPIMShortcode::getInstance();

		return self::$shortcode->get( $args );
	}

	public static function instructions() {
		self::admin_call( "instructions" );
	}

	public static function admin_inventory_items() {
		self::admin_call( "wpim_manage_inventory_items" );
	}

	public static function admin_categories() {
		self::admin_call( "wpim_manage_categories" );
	}

	public static function admin_labels() {
		self::admin_call( "wpim_manage_labels" );
	}

	public static function admin_display() {
		self::admin_call( "wpim_manage_display" );
	}

	public static function admin_settings() {
		self::admin_call( "wpim_manage_settings" );
	}

	public static function admin_statuses() {
		self::admin_call( "wpim_manage_statuses" );
	}

	public static function admin_add_ons() {
		self::admin_call( "wpim_manage_add_ons" );
	}

	public static function admin_call( $method ) {
		self::$admin = WPIMAdmin::getInstance();
		self::$admin->{$method}();
	}

	public static function setup_seo_endpoint() {
		// Add the query var filter
		add_filter( 'query_vars', array( __CLASS__, 'rewrite_variables' ) );

		$seo_urls = (int) self::$config->get( "seo_urls" );

		// add item as a possible "tail" item
		if ( $seo_urls ) {

			$seo_endpoint = self::$config->get( "seo_endpoint", 'inventory' );
			add_rewrite_endpoint( $seo_endpoint, EP_ALL );

			// Ensures the $query_vars['item'] is available
			add_rewrite_tag( "%{$seo_endpoint}%", '([^&]+)' );

			// Requires flushing endpoints whenever the
			// front page is switched to a different page
			$page_on_front = get_option( 'page_on_front' );

			// Match the front page and pass item value as a query var.
			add_rewrite_rule( "^{$seo_endpoint}/([^/]*)/?", 'index.php?page_id=' . $page_on_front . '&' . $seo_endpoint . '=$matches[1]', 'top' );
			// Match non-front page pages.
			add_rewrite_rule( "^(.*)/{$seo_endpoint}/([^/]*)/?", 'index.php?pagename=$matches[1]&static=true&' . $seo_endpoint . '=$matches[2]', 'top' );
		}
	}

	/**
	 * If the shortcode is displayed on the home page, then we need to disable canonical redirects on the home page.
	 *
	 * @param string $redirect
	 *
	 * @return bool
	 */
	public static function disable_canonical_redirect_for_front_page( $redirect ) {
		if ( ! self::$config->get( 'shortcode_on_home' ) ) {
			return $redirect;
		}

		if ( is_page() ) {
			$front_page = get_option( 'page_on_front' );
			if ( $front_page && is_page( $front_page ) ) {
				$redirect = FALSE;
			}
		}

		return $redirect;
	}

	// add seo rewrite endpoint as an allowed query var
	public static function rewrite_variables( $public_query_vars ) {
		// add item as a possible "tail" item
		if ( self::$config->get( 'seo_urls', FALSE ) ) {
			$seo_endpoint        = self::$config->get( 'seo_endpoint', 'wpinventory' );
			$public_query_vars[] = $seo_endpoint;
		}

		return $public_query_vars;
	}

	/**
	 * WordPress admin_enqueue_scripts action callback function
	 */
	public static function admin_enqueue_scripts() {
		if ( self::is_wpinventory_page() ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-datepicker' );

			// Check WP version to get the best version of media upload
			$wp_version = get_bloginfo( 'version' );
			if ( (float) $wp_version >= 3.5 ) {
				wp_enqueue_media();
			} else {
				wp_enqueue_script( 'media-upload' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_style( 'thickbox' );
			}

			wp_register_script( 'wpinventory-admin', self::$url . '/js/wpinventory-admin.js' );
			wp_localize_script( 'wpinventory-admin', 'wpinventory', array(
				'pluginUrl'                    => self::$url,
				'ajaxUrl'                      => admin_url( 'admin-ajax.php' ),
				'nonce'                        => wp_create_nonce( self::NONCE_ACTION ),
				'image_label'                  => self::__( 'Images' ),
				'image_label_singular'         => self::__( 'Image' ),
				'media_label'                  => self::__( 'Media' ),
				'media_label_singular'         => self::__( 'Media' ),
				'insert_button'                => self::__( 'INSERT INTO Item' ),
				'url_label'                    => self::__( 'URL' ),
				'title_label'                  => self::__( 'Title' ),
				'delete_prompt'                => self::__( 'Are you sure you want to delete' ),
				'delete_general'               => self::__( 'this item' ),
				'delete_named'                 => self::__( 'the item' ),
				'save_error'                   => self::__( 'Either %s or %s is required.' ),
				'prompt_qm'                    => self::__( '?' ),
				'support_message_error'        => self::__( 'Please describe your issue or request as completely as possible.' ),
				'support_sending'              => self::__( 'Sending...' ),
				'currency_symbol'              => self::$config->get( 'currency_symbol' ),
				'currency_symbol_location'     => self::$config->get( 'currency_symbol_location' ),
				'currency_thousands_separator' => self::$config->get( 'currency_thousands_separator' ),
				'currency_decimal_separator'   => self::$config->get( 'currency_decimal_separator' ),
				'currency_decimal_precision'   => self::$config->get( 'currency_decimal_precision' )
			) );
			wp_enqueue_script( 'wpinventory-admin' );

			wp_enqueue_style( 'wpinventory', self::$url . '/css/style-admin.css' );
		}
	}

	/**
	 * Wordpress enqueue scripts for the frontend
	 */
	public static function wp_enqueue_scripts() {
		$theme = self::$config->get( 'theme' );
		if ( $theme ) {
			$theme = self::get_theme_url( $theme );
			wp_enqueue_style( 'wpinventory-theme', $theme );
		} else {
			echo '<!-- ' . self::__( 'WP Inventory styles not loaded due to settings in dashboard.' ) . '-->' . PHP_EOL;
		}

		wp_register_script( 'wpinventory-common', self::$url . '/js/wpinventory.js', array( 'jquery' ), WPIMAdmin::VERSION, TRUE );
		wp_localize_script( 'wpinventory-common', 'wpinventory', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' )
		) );
		wp_enqueue_script( 'wpinventory-common' );
	}

	private static function plugin_updater() {

		$add_ons = self::get_full_add_ons();

		if ( ! $add_ons ) {
			return;
		}

		foreach ( $add_ons AS $add_on ) {
			if ( ! empty( $add_on->lkey ) && ! empty( $add_on->version ) ) {
				// setup the updater
				new WPIMUpdater( WPIMAPI::API_URL, $add_on->path, array(
					'version'   => $add_on->version, // current version number
					'license'   => $add_on->lkey, // license key (used get_option above to retrieve from DB)
					'item_name' => $add_on->item_name, // name of this plugin
					'author'    => 'WP Inventory', // author of this plugin
				) );
			}
		}
	}

	private static function get_full_add_ons( $force = FALSE ) {

		$add_ons = get_transient( 'wpim_full_add_ons' );

		if ( $add_ons && ! $force ) {
			return $add_ons;
		}

		// retrieve our license key from the DB
		$reg_info  = self::get_reg_info();
		$add_ons   = self::get_add_ons();
		$add_ons[] = (object) array(
			'title'     => 'WP Inventory Core',
			'item_name' => WPIMAPI::REG_ITEM_NAME,
			'key'       => 'core',
			'version'   => self::VERSION,
			'path'      => __FILE__
		);

		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( (array) $add_ons AS $index => $add_on ) {
			$title = str_ireplace( '/', '', $add_on->title );
			$title = trim( str_replace( '  ', ' ', $title ) );

			foreach ( (array) $reg_info AS $key => $data ) {
				if ( $key == $add_on->key ) {
					$add_on->lkey = $data['key'];
				}
			}

			foreach ( (array) $all_plugins AS $path => $plugin ) {
				$name = str_ireplace( ' and ', ' ', $plugin['Name'] );
				if ( FALSE !== stripos( $name, $title ) && FALSE !== stripos( $name, 'WP Inventory' ) ) {
					$add_on->version = $plugin['Version'];
					$add_on->path    = $path;
				}
			}
		}

		set_transient( 'wpim_full_add_ons', $add_ons, DAY_IN_SECONDS );

		return $add_ons;
	}

	/**
	 * When a plugin updates, re-load the latest versions of the WPIM add-ons to prevent issues
	 *
	 * @param $transient
	 */
	public static function deleted_site_transient( $transient ) {
		if ( 'update_plugins' == $transient ) {
			delete_transient( 'wpim_full_add_ons' );
		}
	}

	/**
	 * AJAX handler to store the state of dismissible notices.
	 */
	public static function ajax_notice_handler() {
		$nonce = self::request( 'nonce' );
		if ( ! $nonce || ! wp_verify_nonce( $nonce, self::NONCE_ACTION ) ) {
			echo 'security error';
			die();
		}

		$type = self::request( 'type' );
		self::notice_dismissed( $type, TRUE );
		die();
	}

}

// Instantiate the class
add_action( 'plugins_loaded', array( 'WPInventoryInit', 'initialize' ) );
