<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract Database class for accessing inventory data
 * @author    WP Inventory Manager
 * @package   WPInventory
 * @copyright 2013 - WP Inventory Manager
 */
class WPIMDB extends WPIMCore {

	private static $instance = NULL;

	protected $wpdb;

	/**
	 * Name of Core inventory table.  Stores inventory items.
	 * @var string
	 */
	protected $inventory_table;

	/**
	 * Name of category table.  Stores category names.
	 * @var string
	 */
	protected $category_table;

	/**
	 * Name of inventory images table.  Stores images for items.  Supports multiple images per item.
	 * @var string
	 */
	protected $image_table;

	/**
	 * Name of inventory media table.  Stores items such as PDF's, docs, etc.  Supports multiple media per item.
	 * @var string
	 */
	protected $media_table;

	/**
	 * Name of inventory status table.  Allows for various statii to be supported.  This version only supports active /
	 * inactive
	 * @var string
	 */
	protected $status_table;

	/**
	 * Name of inventory labels table.  Allows for custom field labels / renaming.  This version only supports custom
	 * labels, not types.
	 * @var string
	 */
	protected $label_table;

	/**
	 * Constructor magic method.
	 */
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;

		self::$config = WPIMConfig::getInstance();

		$this->set_table_names();
		$this->check_tables();
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
	 * Define the names of the tables used throughout
	 */
	public function set_table_names() {
		global $table_prefix;
		$this->inventory_table = $table_prefix . 'wpinventory_item';
		$this->category_table  = $table_prefix . 'wpinventory_category';
		$this->image_table     = $table_prefix . 'wpinventory_image';
		$this->media_table     = $table_prefix . 'wpinventory_media';
		$this->status_table    = $table_prefix . 'wpinventory_status';
		$this->label_table     = $table_prefix . 'wpinventory_label';
		// Types is available in pro version
		// Ledger / history table / structure is available in pro version
		// Additional / custom fields available in pro version
	}

	/**
	 * Ensures that the SEO slug is unique
	 *
	 * @param string  $slug
	 * @param string  $name
	 * @param integer $id
	 */
	public function validate_slug( $type, $slug, $name, $id ) {
		if ( $id && ! $slug ) {
			$slug = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT inventory_slug FROM ' . $this->inventory_table . ' WHERE inventory_id = %d', $id ) );
		}

		if ( ! $slug ) {
			$slug = str_replace( ' ', '-', $name );
			$slug = strtolower( preg_replace( '/[^\da-z_-]/i', '', $name ) );
		}
		$inv_exists_id = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT inventory_id
			FROM ' . $this->inventory_table . ' 
			WHERE inventory_slug = %s', $slug ) );

		if ( $type == 'inventory' && $inv_exists_id == $id ) {
			$inv_exists_id = NULL;
		}

		$cat_exists_id = $this->wpdb->get_var( $this->wpdb->prepare( 'SELECT count(*)
			FROM ' . $this->category_table . '
			WHERE category_slug = %s', $slug ) );

		if ( $type == 'category' && $cat_exists_id == $id ) {
			$cat_exists_id = NULL;
		}

		if ( $inv_exists_id || $cat_exists_id ) {
			$slug .= '-' . $id;
		}

		return $slug;

	}

	/**
	 * Check for sort column clicked, and verify as legitimate field
	 *
	 * @param string $order        - the default order
	 * @param array  $fields       - array of allowed fields name
	 * @param bool   $return_array - flag whether to return array or string
	 *
	 * @return string
	 */
	public function parse_sort( $order, $fields, $return_array = FALSE ) {

		$fields = apply_filters( 'wpim_allowed_sort_fields', $fields );

		$sortby          = ( isset( $_GET['sortby'] ) ) ? $_GET['sortby'] : '';
		$default_sortdir = ( stripos( $order, '_date_' ) !== FALSE || stripos( $sortby, '_date_' ) !== FALSE ) ? 'DESC ' : 'ASC';
		$sortdir         = ( isset( $_GET['sortdir'] ) ) ? $_GET['sortdir'] : $default_sortdir;

		if ( $sortby ) {
			if ( in_array( $sortby, (array) $fields ) ) {
				$order = $sortby;
			}

			// $order.= (strtolower($sortdir) == 'desc') ? ' DESC' : '';
		}

		if ( ! array( $order ) && stripos( $order, ',' ) !== FALSE ) {
			$order = explode( ',', $order );
		}

		$new_order = array();

		foreach ( (array) $order AS $sby ) {
			if ( in_array( preg_replace( '/ DESC$/', '', $sby ), (array) $fields ) ) {
				$new_order[] = $sby;
			} else if ( in_array( 'inventory_' . $sby, (array) $fields ) ) {
				$new_order[] = 'inventory_' . $sby;
			} else if ( 'category_name' == $sby || 'category_id' == $sby ) {
				$new_order[] = 'category_name';
			}
		}

		$numeric = self::labels()->get_numeric();
		$numeric = apply_filters( 'wpim_parse_sort_numeric', $numeric );

		$new_order = (array) $new_order;
		foreach ( $new_order AS $i => $field ) {
			if ( 'category_id' == $field ) {
				$new_order[ $i ] = 'category_name';
			}
			if ( in_array( $field, $numeric ) ) {
				$new_order[ $i ] = 'CAST(`' . $field . '` AS DECIMAL)';
			}
		}

		$new_order = apply_filters( 'wpim_parse_order', $new_order );

		$order = implode( ',', $new_order );
		$order = str_ireplace( 'category_id', 'category_name', $order );

		$dir = '';
		if ( ! preg_match( "/(ASC|DESC)$/i", $order ) ) {
			$dir = ( strtolower( trim( $sortdir ) ) == 'desc' ) ? ' DESC' : '';
		}

		$order = array(
			'order' => $order,
			'dir'   => $dir
		);

		return ( ! $return_array ) ? implode( ' ', $order ) : $order;
	}

	/**
	 * Convert date / time to mysql format
	 *
	 * @param mixed $date
	 * @param mixed $time
	 *
	 * @return string
	 */
	protected function date_to_mysql( $date, $time = FALSE ) {
		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}

		$format = ( $time ) ? 'Y-m-d H:i:s' : 'Y-m-d';

		return date( $format, $date );
	}

	/**
	 * Parse an array of rows of data from the database
	 *
	 * @param array $records
	 *
	 * @return array
	 */
	public function parseFromDb( $records ) {
		if ( is_array( $records ) ) {
			foreach ( $records AS $key => $row ) {
				$records[ $key ] = $this->parseRowFromdb( $row );
			}
		} else if ( is_object( $records ) ) {
			$records = $this->parseRowFromDb( $records );
		}

		return $records;
	}

	/**
	 * Prepare a single row of data
	 *
	 * @param object|array $row
	 *
	 * @return array|object
	 */
	public function parseRowFromDb( $row ) {
		$is_object = ( is_object( $row ) ) ? TRUE : FALSE;
		$row       = (array) $row;
		foreach ( $row AS $key => $value ) {
			// $row[$key] = htmlspecialchars($value);
		}

		return ( $is_object ) ? (object) $row : $row;
	}

	/**
	 * Determine if the necessary tables exist, and if not, create them
	 */
	public function activate_plugin() {
		$db = new self;
		$db->check_tables();
	}

	private function check_tables() {
		// Database updates.  Incremented and tracked by version
		$inventory_version = self::$config->get( 'version', 0 );

		// Initial Install - set up tables 
		if ( ! (float) $inventory_version ) {

			$tables = $this->getDBTables();

			// Check for existence of main inventory table
			if ( ! in_array( $this->inventory_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->inventory_table . "` (
				  `inventory_id` INT(11) NOT NULL AUTO_INCREMENT,
				  `inventory_number` TEXT NOT NULL,
				  `inventory_name` TEXT NULL,
				  `inventory_description` TEXT NULL,
				  `inventory_size` TEXT NULL,
				  `inventory_manufacturer` TEXT NULL,
				  `inventory_make` TEXT NULL,
				  `inventory_model` TEXT NULL,
				  `inventory_year` TEXT NULL,
				  `inventory_serial` TEXT NULL,
				  `inventory_fob` TEXT NULL,
				  `inventory_quantity` INT(11) NOT NULL DEFAULT 0,
				  `inventory_quantity_reserved` INT(11) NOT NULL DEFAULT 0,
				  `inventory_price` FLOAT DEFAULT NULL,
				  `status_id` INT(11) NOT NULL DEFAULT 0,
				  `inventory_slug` VARCHAR(255) NULL,
				  `inventory_sort_order` INT(11) NOT NULL DEFAULT 0,
				  `category_id` INT(11) NOT NULL DEFAULT 1,
				  `user_id` INT(11) NOT NULL,
				  `inventory_date_added` DATETIME NULL,
				  `inventory_date_updated` DATETIME NULL,
				  PRIMARY KEY (`inventory_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of category table
			if ( ! in_array( $this->category_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->category_table . "` (
				`category_id` INT(11) NOT NULL AUTO_INCREMENT,
				`category_name` VARCHAR(255) NOT NULL,
				`category_description` VARCHAR(255) NOT NULL,
				`category_slug` VARCHAR(255) NULL,
				`category_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`category_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of image table
			if ( ! in_array( $this->image_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->image_table . "` (
				`image_id` INT(11) NOT NULL AUTO_INCREMENT,
				`inventory_id` INT(11) NOT NULL,
				`post_id` VARCHAR(255) NOT NULL,
				`image` TEXT NOT NULL,
				`thumbnail` TEXT NOT NULL,
				`medium` TEXT NOT NULL,
				`large` TEXT NOT NULL,
				`image_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`image_id`),
				KEY `inventory_id` (`inventory_id`),
				KEY `post_id` (`post_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of media table
			if ( ! in_array( $this->media_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->media_table . "` (
				`media_id` INT(11) NOT NULL AUTO_INCREMENT,
				`inventory_id` INT(11) NOT NULL,
				`media_title` VARCHAR(255) NOT NULL,
				`media` TEXT NOT NULL,
				`media_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`media_id`),
				KEY `inventory_id` (`inventory_id`)
				);";
				$this->wpdb->query( $sql );
			}

			// Check for existence of status table
			if ( ! in_array( $this->status_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->status_table . "` (
				`status_id` INT(11) NOT NULL AUTO_INCREMENT,
				`status_name` VARCHAR(255) NOT NULL,
				`status_description` VARCHAR(255) NOT NULL,
				`status_sort_order` INT(11) NOT NULL DEFAULT 0,
				PRIMARY KEY (`status_id`)
				);";
				$this->wpdb->query( $sql );
			}

			self::$config->set( "version", "0.1" );
		}

		/*
		 * Version 0.2
		 *  - Add label fields
		 */
		if ( version_compare( $inventory_version, "0.0.2" ) < 0 ) {

			$tables = $this->getDBTables();

			// Check for existence of status table
			if ( ! in_array( $this->label_table, $tables ) ) {
				$sql = "CREATE TABLE IF NOT EXISTS `" . $this->label_table . "` (
				`label_id` INT(11) NOT NULL AUTO_INCREMENT,
				`label_field` VARCHAR(255) NOT NULL,
				`label_label` VARCHAR(255) NOT NULL,
				PRIMARY KEY (`label_id`)
				);";
				$this->wpdb->query( $sql );
			}
			self::$config->set( "version", 0.2 );
		}

		/*
			* Version 0.3
			*  - Add status fields to inventory table
			*/
		if ( version_compare( $inventory_version, "0.0.3" ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->inventory_table . "`
   				ADD COLUMN inventory_status INT(11) NOT NULL DEFAULT 1
   				";
			$this->wpdb->query( $sql );
			self::$config->set( "version", 0.3 );
		}

		/*
			* Version 0.4
			*  - Add in_use field to label table
			*/
		if ( version_compare( $inventory_version, "0.0.4" ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->label_table . "`
   			ADD COLUMN is_used TINYINT(4) NOT NULL DEFAULT 1
   			";
			$this->wpdb->query( $sql );
			self::$config->set( "version", 0.4 );
		}

		/*
		 * Version 1.0.8
		 * - Ack.  Convert to utf-8 - Greek customer having issues!
		 */
		if ( version_compare( $inventory_version, "1.0.8" ) < 0 ) {

			// Make a backup
			$sql = "CREATE TABLE " . $this->inventory_table . "_backup LIKE " . $this->inventory_table;
			$this->wpdb->query( $sql );

			// Update to ut8
			$sql = "INSERT " . $this->inventory_table . "_backup SELECT * FROM " . $this->inventory_table;
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->inventory_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->inventory_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->category_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->category_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->image_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->image_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->label_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->label_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->media_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->media_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->status_table . " CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE " . $this->status_table . " CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
			$this->wpdb->query( $sql );

			self::$config->set( "version", '1.0.8' );
		}

		/*
   		 * Version 1.1.6
   		 *  - Add numeric field to label table (to indicate if should be sorted numerically)
   		 */
		if ( version_compare( $inventory_version, "1.1.6" ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->label_table . "`
   			ADD COLUMN is_numeric TINYINT(4) NOT NULL DEFAULT 0
   			";
			$this->wpdb->query( $sql );
			self::$config->set( "version", '1.1.6' );
		}

		/*
   		 * Version 1.2.2
   		 *  - Add status "inactive" field to properly integrate status control
		 *  - Insert statuses, update items
		 *  - Remove vestigial status_id column
   		 */
		if ( version_compare( $inventory_version, "1.2.2" ) < 0 ) {
			$sql = "ALTER TABLE `" . $this->status_table . "`
   			ADD COLUMN is_active TINYINT(4) NOT NULL DEFAULT 0
   			";
			$this->wpdb->query( $sql );

			$sql = "INSERT INTO `" . $this->status_table . "` (status_id, status_name, status_description, status_sort_order, is_active)
			 VALUES (1, 'Inactive', 'Inactive (default status)', 0, 0),
			  (2, 'Active', 'Active (default status)', 1, 1)";
			$this->wpdb->query( $sql );

			$sql = "ALTER TABLE `" . $this->inventory_table . "` DROP COLUMN `status_id`";
			$this->wpdb->query( $sql );

			$sql = "UPDATE `" . $this->inventory_table . "` SET inventory_status = 2";
			$this->wpdb->query( $sql );

			self::$config->set( "version", '1.2.2' );
		}


		if ( version_compare( $inventory_version, "1.2.3" ) < 0 ) {
			$sql = "ALTER TABLE " . $this->inventory_table . " ADD INDEX `category_id` (`category_id`)";
			$this->wpdb->query( $sql );

			self::$config->set( "version", '1.2.3' );
		}

		if ( version_compare( $inventory_version, "1.2.4" ) < 0 ) {
			$sql = "ALTER TABLE " . $this->inventory_table . " ADD INDEX `inventory_status` (`inventory_status`)";
			$this->wpdb->query( $sql );

			self::$config->set( "version", '1.2.4' );
		}

		do_action( 'wpim_activate_plugin' );
	}

	protected function get_error() {
		return $this->wpdb->last_error;
	}

	public function get_message() {
		return self::$error;
	}

	protected function append_where( $sql, $clause, $and = 'AND' ) {
		if ( trim( $sql ) ) {
			$sql .= ' ' . $and;
		}

		return $sql . ' ' . $clause;
	}

	protected function getDBTables() {
		// We are checking enough tables it makes sense to just build an array with all existing tables,
		// Rather than run the check table query multiple times
		$fulltables = $this->wpdb->get_results( "SHOW TABLES", ARRAY_N );

		// Put into an array that's a bit easier to use
		foreach ( $fulltables as $table ) {
			$tables[ $table[0] ] = $table[0];
		}

		return $tables;
	}
}
