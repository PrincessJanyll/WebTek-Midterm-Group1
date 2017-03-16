<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPIMLabel extends WPIMDB {

	private static $instance;

	private static $labels;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		parent::__construct();
		$this->load();
	}

	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * This is here purely to prevent someone from cloning the class
	 */
	private function __clone() {
	}

	/**
	 * Get a listing of all custom labels.
	 */
	public function get_all() {

		if ( ! self::$labels ) {
			$this->load();
		}

		return self::$labels;
	}

	/**
	 * Get specific label
	 *
	 * @param string $field_name
	 *
	 * @return string
	 */
	public function get( $field_name ) {
		return ( ! empty( self::$labels[ $field_name ] ) ) ? self::$labels[ $field_name ] : $field_name;
	}

	public function get_numeric() {
		$labels = $this->get_all();
		$labels = array_filter( $labels, array( $this, 'is_numeric' ) );

		return ( ! empty( $labels ) ) ? array_keys( $labels ) : array();
	}

	public function is_numeric( $data ) {
		return ( ! $data['is_numeric'] ) ? FALSE : TRUE;
	}

	/**
	 * Get the field name from a specific label
	 *
	 * @param string $label
	 *
	 * @return string
	 */
	public function find_field( $label ) {
		if ( $label && ! empty( self::$labels ) ) {
			foreach ( self::$labels AS $field => $labels ) {
				$default = (isset($labels['default'])) ? $labels['default'] : $field;
				if ( $default == $label || $labels['label'] == $label ) {
					return $field;
				}
			}
		}
	}

	public function reset() {
		$is_used = array();
		foreach ( $this->default_labels() AS $field => $label ) {
			$is_used[ $field ] = 1;
		}
		$this->save( $this->default_labels(), $is_used );
	}

	private function load() {
		$label_data = $this->wpdb->get_results( 'SELECT * FROM ' . $this->label_table . ' AS l' );

		// Load defaults
		$labels = self::default_labels();

		foreach ( $labels AS $field => $default ) {
			$labels[ $field ] = array(
				"default"    => $default,
				"label"      => $default,
				"is_used"    => TRUE,
				"is_numeric" => FALSE
			);
		}

		// Overload any set labels
		foreach ( $label_data AS $label ) {
			$labels[ $label->label_field ]['label']      = $label->label_label;
			$labels[ $label->label_field ]['is_used']    = ( $this->is_always_on( $label->label_field ) || $label->is_used ) ? TRUE : FALSE;
			$labels[ $label->label_field ]['is_numeric'] = ( $label->is_numeric ) ? TRUE : FALSE;
		}

		$labels = apply_filters( 'wpim_load_labels', $labels );

		// We don't want the id set up in this configuration
		if ( isset( $labels['inventory_id'] ) ) {
			unset( $labels['inventory_id'] );
		}

		uasort( $labels, array( __CLASS__, 'sortNotUsed' ) );

		self::$labels = $labels;
	}

	private function sortNotUsed( $a, $b ) {
		if ( $a['is_used'] == $b['is_used'] ) {
			$val = ( strcasecmp( $a['label'], $b['label'] ) < 0 ) ? - 1 : 1;

			return $val;
		}

		return ( $a['is_used'] < $b['is_used'] ) ? 1 : - 1;
	}

	public static function default_labels() {
		$defaults = array(
			'inventory_number'            => WPInventoryInit::__( 'Number' ),
			'inventory_name'              => WPInventoryInit::__( 'Name' ),
			'inventory_image'             => WPInventoryInit::__( 'Image' ),
			'inventory_images'            => WPInventoryInit::__( 'Images' ),
			'inventory_media'             => WPInventoryInit::__( 'Media' ),
			'inventory_description'       => WPInventoryInit::__( 'Description' ),
			'inventory_size'              => WPInventoryInit::__( 'Size' ),
			'inventory_manufacturer'      => WPInventoryInit::__( 'Manufacturer' ),
			'inventory_make'              => WPInventoryInit::__( 'Make' ),
			'inventory_model'             => WPInventoryInit::__( 'Model' ),
			'inventory_year'              => WPInventoryInit::__( 'Year' ),
			'inventory_serial'            => WPInventoryInit::__( 'Serial #' ),
			'inventory_fob'               => WPInventoryInit::__( 'FOB' ),
			'inventory_quantity'          => WPInventoryInit::__( 'Quantity' ),
			'inventory_quantity_reserved' => WPInventoryInit::__( 'Quantity Reserved' ),
			'inventory_price'             => WPInventoryInit::__( 'Price' ),
			'inventory_status'            => WPInventoryInit::__( 'Status' ),
			'inventory_slug'              => WPInventoryInit::__( 'Slug' ),
			'inventory_sort_order'        => WPInventoryInit::__( 'Sort Order' ),
			'category_id'                 => WPInventoryInit::__( 'Category' ),
			'user_id'                     => WPInventoryInit::__( 'User' ),
			'inventory_date_added'        => WPInventoryInit::__( 'Date Added' ),
			'inventory_date_updated'      => WPInventoryInit::__( 'Date Updated' )
		);

		return apply_filters( 'wpim_default_labels', $defaults );
	}

	/**
	 * Returns the set of elements that must always be on
	 *
	 * @return array
	 */
	public function always_on() {
		return apply_filters( 'wpim_always_on_labels', array(
			'inventory_name',
			'inventory_number',
			'inventory_date_added',
			'inventory_date_updated',
			'inventory_sort_order'
		) );
	}

	public function always_on_admin() {
		$array = $this->always_on();

		return apply_filters( 'wpim_always_on_admin_labels', array_merge( $array, array(
			'category_id',
			'inventory_status',
			'inventory_quantity',
			'inventory_quantity_reserved',
			'user_id'
		) ) );
	}

	/**
	 * Fields that should not be exposed via the admin edit interface
	 *
	 * @return array
	 */
	public function internal_use() {
		return apply_filters( 'wpim_internal_use_labels', array(
			'inventory_date_added',
			'inventory_date_updated',
			'user_id'
		) );
	}

	public function immutable() {
		return apply_filters( 'wpim_immutable_labels', array(
			'inventory_date_added',
			'inventory_date_updated',
			'inventory_sort_order',
			'inventory_image',
			'inventory_images',
			'inventory_slug',
			'inventory_status',
			'inventory_media',
			'user_id',
			'category_id',
			'inventory_quantity',
			'inventory_quantity_reserved'
		) );
	}

	public function is_always_on( $field ) {
		return ( in_array( $field, $this->always_on() ) ) ? TRUE : FALSE;
	}

	public function is_always_on_admin( $field ) {
		return ( in_array( $field, $this->always_on_admin() ) ) ? TRUE : FALSE;
	}

	public function is_internal_use( $field ) {
		return ( in_array( $field, $this->internal_use() ) ) ? TRUE : FALSE;
	}

	public function is_immutable( $field ) {
		return ( in_array( $field, $this->immutable() ) ) ? TRUE : FALSE;
	}

	public function save( $data, $used_data, $numeric_data = array() ) {

		if ( ! self::$labels ) {
			self::load();
		}

		foreach ( $data AS $field => $label ) {
			$label                  = ( is_array( $label ) ) ? $label['label'] : $label;
			self::$labels[ $field ] = $label;
		}

		$this->wpdb->query( "DELETE FROM " . $this->label_table );

		$query = '';
		foreach ( self::$labels AS $field => $label ) {
			$is_used    = ( ! empty( $used_data[ $field ] ) ) ? 1 : 0;
			$is_numeric = ( ! empty( $numeric_data[ $field ] ) ) ? 1 : 0;
			$query .= ( $query ) ? ',' : '';
			$query .= $this->wpdb->prepare( '(%s, %s, %d, %d)', $field, $label, $is_used, $is_numeric );
		}

		$this->wpdb->query( "INSERT INTO " . $this->label_table . " (label_field, label_label, is_used, is_numeric) VALUES " . $query );

		self::load();

		return ( ! $this->wpdb->last_error ) ? TRUE : FALSE;
	}
}
