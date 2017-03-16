<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inventory configuration class
 */
class WPIMConfig {

	private static $instance;

	private static $config;

	public static $query_vars;

	public static $SETTINGS;
	public static $SETTINGS_GROUP;

	public function __construct() {
		self::$SETTINGS       = WPInventoryInit::SETTINGS;
		self::$SETTINGS_GROUP = WPInventoryInit::SETTINGS_GROUP;
		self::loadConfig();
	}

	public static function getInstance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_all() {
		return self::$config;
	}

	public function get( $field, $default = NULL ) {
		if ( ! self::$config ) {
			self::loadConfig();
		}

		$setting = ( ! isset( self::$config[ $field ] ) ) ? $default : self::$config[ $field ];

		return apply_filters( 'wpim_get_config', $setting, $field );
	}

	public function set( $field, $value ) {
		if ( $field == 'seo_endpoint' && $value != self::$config[ $field ] ) {
			self::$config['flush_rewrite'] = 1;
		}

		if ( FALSE !== stripos( $field, 'license_key' ) ) {
			$value = trim( $value );
		}

		self::$config[ $field ] = $value;
		update_option( self::$SETTINGS, self::$config );
		self::loadConfig();
	}

	public static function loadConfig() {
		self::$config = get_option( self::$SETTINGS );

		$defaults = self::defaults();
		foreach ( $defaults AS $field => $default ) {
			if ( ! isset( self::$config[ $field ] ) ) {
				self::$config[ $field ] = $default;
			}
		}
	}

	public static function defaults() {
		$defaults = array(
			// License info
			'license_key'                  => '',

			// Currency formatting.  Names are pretty clear.
			'currency_symbol'              => '$',
			'currency_symbol_location'     => 0, // 0 = Start, 1 = End
			'currency_thousands_separator' => ',',
			'currency_decimal_separator'   => '.',
			'currency_decimal_precision'   => '2',

			// Date format.  Uses PHP formats: http://php.net/manual/en/function.date.php
			'date_format'                  => 'm/d/Y',
			'time_format'                  => '',

			// Lowest role of user that can manage inventory
			'permissions_lowest_role'      => 'manage_options',
			// Restrict user to own equipment?
			'permissions_user_restricted'  => 0,

			'reserve_allow'           => 1,
			'reserve_quantity'        => 1,
			'reserve_decrement'       => 1,
			'reserve_require_name'    => 2,
			'reserve_require_address' => 1,
			'reserve_require_city'    => 1,
			'reserve_require_state'   => 1,
			'reserve_require_zip'     => 1,
			'reserve_require_phone'   => 1,
			'reserve_require_email'   => 2,
			'reserve_require_message' => 1,
			'reserve_label_name'      => WPIMCore::__( 'Name' ),
			'reserve_label_address'   => WPIMCore::__( 'Address' ),
			'reserve_label_city'      => WPIMCore::__( 'City' ),
			'reserve_label_state'     => WPIMCore::__( 'State' ),
			'reserve_label_zip'       => WPIMCore::__( 'Zip' ),
			'reserve_label_phone'     => WPIMCore::__( 'Phone' ),
			'reserve_label_email'     => WPIMCore::__( 'Email' ),
			'reserve_label_message'   => WPIMCore::__( 'Message' ),
			'reserve_label_email'     => WPIMCore::__( 'Email' ),
			'reserve_label_quantity'  => WPIMCore::__( 'Quantity' ),
			'reserve_label_button'    => WPIMCore::__( 'Reserve' ),
			'reserve_email'           => '',
			'reserve_confirmation'    => 1,

			// Use media upload
			'use_media'               => 1,

			// Items per page (pagination
			'page_size'               => 20,

			'hide_low'          => 0,
			'hide_low_quantity' => 0,

			// Search setting
			'include_in_search' => '',
			'search_page_id'    => 0,

			'seo_urls'                   => 1,
			'seo_endpoint'               => 'inventory',
			'shortcode_on_home'          => 0,

			// Display defaults
			'display_listing_labels'     => 1,
			'display_listing_table'      => 1,
			'display_detail_labels'      => 1,
			'display_listing_image_size' => 'thumbnail',
			'display_detail_image_size'  => 'large',

			'display_admin' => 'inventory_number,inventory_name,category_id,inventory_serial,inventory_sort_order',

			'theme' => 'gray-theme',

			'placeholder_image'      => '',
			'open_images_new_window' => 1,
			'open_media_new_window'  => 1
		);

		return apply_filters( 'wpim_default_config', $defaults );
	}
}