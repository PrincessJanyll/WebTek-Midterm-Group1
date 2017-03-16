<?php

// No direct access allowed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class WPIMCore {

	const VERSION = '1.4.3';

	const MIN_PHP_VERSION = '5.5';

	const SHORTCODE = 'wpinventory';

	const SETTINGS = 'wpinventory_settings';
	const SETTINGS_GROUP = 'wpinventory_settings_group';

	const VIEWFOLDER = 'wpinventory/views/';
	const LANG = 'wpinventory';
	const MENU = 'wpinventory';
	const NONCE_ACTION = 'wpinventory_&%k2s$%#!@#8vY^';

	const SUPPORT_CLASS = 'WPIMSupport';

	protected static $filters = array(
		"inventory_search"      => "search",
		"inventory_sort_by"     => "order",
		"inventory_category_id" => "category_id",
		"inventory_status"      => "inventory_status",
		"inventory_page"        => "page"
	);

	protected static $options;

	protected static $url;

	protected static $self_url;

	protected static $seo_urls = FALSE;

	protected static $seo_endpoint;

	protected static $path;

	protected static $theme_path = '/themes/css/';

	protected static $config;

	protected static $label;

	protected static $status;

	protected static $api;

	protected static $admin;

	protected static $shortcode;

	protected static $add_ons;

	protected static $message;

	protected static $error;

	protected static $plugin_errors = array();

	private static $editor_rows = 4;

	private static $teeny = FALSE;

	private static $date_format;

	protected static $notices;

	/**
	 * Sort variables
	 */
	protected static $sortdir;
	protected static $sortby;

	/**
	 * Array of admin page slugs to keep track of where to include scripts / css
	 * @var array
	 */
	protected static $pages;

	public function __construct() {
		self::$config       = WPIMConfig::getInstance();
		self::$label        = WPIMLabel::getInstance();
		self::$status       = WPIMStatus::getInstance();
		self::$seo_urls     = self::getOption( 'seo_urls' );
		self::$seo_endpoint = self::getOption( 'seo_endpoint' );
		self::$api          = WPIMAPI::getInstance();

		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
	}

	private function __clone() {
	}

	public static function min_php_version() {
		echo '<div class="notice notice-error"><p><strong>' . self::__( 'IMPORTANT!' ) . '</strong><br>' . sprintf( self::__( 'Your server is using version %s of PHP, which is over 6 years old, not maintained, and exposes your website to attack.' ), phpversion() );
		echo '<br><strong>' . sprintf( self::__( ' WP Inventory requires version %s or higher, so it is not loaded.' ), self::MIN_PHP_VERSION ) . '</strong>';
		echo '<p>' . sprintf( self::__( 'This is normally easy to correct.  Contact your host provider and ask them to upgrade you to at least PHP version %s' ), self::MIN_PHP_VERSION );
		echo '<br>' . self::__( 'It is very insecure to use this old version of PHP, so we strongly recommend upgrading, even if you choose not to use WP Inventory.' ) . '</p>';
		echo '</div>';
	}

	public static function get_version() {
		$version = array();
		list( $version['major'], $version['minor'], $version['sub'] ) = explode( '.', self::VERSION );
		foreach ( $version AS $key => $v ) {
			$version[ $key ] = (int) $v;
		}

		return $version;
	}

	public static function check_version( $min_version, $message ) {
		$display_version = $min_version;
		$min_version     = explode( '.', $min_version );
		foreach ( $min_version AS $i => $v ) {
			$min_version[ $i ] = (int) $v;
		}

		$version = self::get_version();
		$valid   = TRUE;

		if ( $min_version[0] > $version['major'] ) {
			$valid = FALSE;
		} else if ( $min_version[0] == $version['major'] ) {
			if ( $min_version[1] > $version['minor'] ) {
				$valid = FALSE;
			} else if ( $min_version[1] == $version['minor'] ) {
				if ( $min_version[2] > $version['sub'] ) {
					$valid = FALSE;
				}
			}
		}

		if ( $valid ) {
			// Hi there!
			// If you are here in an effort to work around our license requirements, please
			// give us a shout.  We work hard to make this product affordable, and we have
			// thousands of hours in development.  We would appreciate it if you
			// would give us the chance to help you without defeating the license.
			// Besides - the license entitles you to great benefits, like automatic updates!
			if ( ! self::is_honest_user() ) {
				self::$plugin_errors[] = $message . ' ' . self::__( 'requires a licensed copy of Inventory Manager.' );

				return FALSE;
			}

			return TRUE;
		}

		self::$plugin_errors[] = $message . ' ' . self::__( 'requires inventory version' ) . ' ' . $display_version . ' ' . self::__( 'or higher, so it is not activated.' );
	}

	public static function check_add_on_version( $add_on, $min_version, $add_on_required = FALSE ) {

		$is_installed = self::is_add_on_installed( $add_on );

		if ( ! $is_installed && ! $add_on_required ) {
			return TRUE;
		}

		if ( ! $is_installed && $add_on_required ) {
			return FALSE;
		}

		if ( is_bool( $is_installed ) ) {
			return FALSE;
		}

		return version_compare( $is_installed, $min_version );
	}

	/**
	 * Utility function to get $_GET / $_POST values by key
	 *
	 * @param string $key
	 * @param mixed  $default
	 *
	 * @return mixed
	 */
	public static function request( $key, $default = NULL ) {
		$var = ( isset( $_GET[ $key ] ) ) ? $_GET[ $key ] : $default;
		$var = ( isset( $_POST[ $key ] ) ) ? $_POST[ $key ] : $var;

		return $var;
	}

	/**
	 * Abstraction of the WP language function.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function __( $text ) {
		return __( $text, self::LANG );
	}

	/**
	 * Abstraction of the WP language function (echo)
	 *
	 * @param string $text
	 */
	public static function _e( $text ) {
		echo self::__( $text );
	}

	/**
	 * Converts a string such as item name or category name into a css-safe class name
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	public static function get_class( $string ) {
		return str_replace( ' ', '_', strtolower( $string ) );
	}

	public static function get_category_permalink( $page_id, $category_id, $category_name = NULL ) {
		$url = get_permalink( $page_id );
		$sep = ( stripos( $url, "?" ) !== FALSE ) ? '&' : '?';
		$url = rtrim( $url, '/' );

		// TODO: Enable Category SEO URLs
		return $url . $sep . 'category_id=' . $category_id;
	}

	public static function get_pagination_permalink( $url, $page ) {
		// $url = get_permalink($page_id);
		$sep = ( stripos( $url, "?" ) !== FALSE ) ? '&' : '?';
		$url = rtrim( $url, '/' );

		$args = wpinventory_get_filter_criteria();

		unset( $args['search'] );
		unset( $args['sort'] );
		unset( $args['caller'] );

		$sortparam = http_build_query( $args );

		// TODO: Enable Category SEO URLs
		$permalink = $url . $sep . 'inventory_page=' . $page . '&' . $sortparam;
		$permalink = apply_filters( 'wpim_pagination_permalink', $permalink, $url, $sep, $page, $sortparam );

		return $permalink;
	}

	/**
	 * Store the page state for excellent back-link functionality
	 *
	 * @param boolean $is_single
	 */
	public static function store_page_state( $is_single = FALSE ) {

		$update_transient = FALSE;
		// Loop through all filters
		$page_state = array();
		foreach ( self::$filters AS $filter => $field ) {
			if ( self::request( $filter ) !== NULL ) {
				$update_transient      = TRUE;
				$page_state[ $filter ] = self::request( $filter );
			}
		}

		// Never force if we're viewing a single item
		if ( $is_single ) {
			$update_transient = FALSE;
		}

		// Store as a transient value
		if ( $update_transient ) {
			global $post;
			$page_state['post_id']              = $post->ID;
			$_SESSION['wpinventory_page_state'] = $page_state;
		}
	}

	public static function get_page_state() {
		return ( isset( $_SESSION['wpinventory_page_state'] ) ) ? (array) $_SESSION['wpinventory_page_state'] : array();
	}

	/**
	 * Formats the date passed in using the inventory settings
	 */
	public static function format_date( $date, $format = '' ) {
		if ( ! $format ) {
			$date_format = self::$config->get( 'date_format' );
			$time_format = self::$config->get( 'time_format' );
		} else {
			$date_format = $format;
			$time_format = '';
		}

		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}

		if ( $time_format ) {
			$date_format .= ' ' . $time_format;
		}

		$newdate = date( $date_format, $date );

		return $newdate;
	}

	/**
	 * Formats the currency passed in using the inventory settings
	 */
	public static function format_currency( $amount ) {
		$currency_symbol              = trim( self::$config->get( 'currency_symbol', '$' ) );
		$currency_symbol_location     = self::$config->get( 'currency_symbol_location' );
		$currency_thousands_separator = trim( self::$config->get( 'currency_thousands_separator', ',' ) );
		$currency_decimal_separator   = trim( self::$config->get( 'currency_decimal_separator', '.' ) );
		$currency_decimal_precision   = self::$config->get( 'currency_decimal_precision', 2 );
		$thousands                    = floor( $amount / 1000 );
		$remainder                    = (int) ( $amount - ( $thousands * 1000 ) );
		$thousands                    = self::format_thousands( $thousands, $currency_thousands_separator );
		if ( $currency_decimal_precision && $currency_decimal_separator ) {
			$decimals = round( ( $amount - (int) $amount ) * pow( 10, $currency_decimal_precision ) );
			$decimals = substr( str_pad( $decimals, $currency_decimal_precision, '0', STR_PAD_LEFT ), - 1 * $currency_decimal_precision );
			$decimals = substr( str_pad( $decimals, $currency_decimal_precision, '0' ), 0, $currency_decimal_precision );
		} else {
			$decimals                   = '';
			$currency_decimal_separator = '';
		}
		$number = ( ! (int) $currency_symbol_location ) ? $currency_symbol : '';
		$number .= ( $thousands ) ? $thousands . $currency_thousands_separator : '';
		$number .= ( $thousands ) ? substr( str_pad( $remainder, 3, '0', STR_PAD_LEFT ), 0, 3 ) : $remainder;
		$number .= $currency_decimal_separator . $decimals;
		$number .= ( (int) $currency_symbol_location ) ? $currency_symbol : '';

		return $number;
	}

	private static function format_thousands( $number, $separator ) {
		return strrev( implode( str_split( strrev( $number ), 3 ), $separator ) );
	}


	protected static function output_errors() {
		$content = '';

		if ( self::$plugin_errors ) {

			if ( ! self::is_wpinventory_page() && self::notice_dismissed( 'plugin-core-required' ) ) {
				return;
			}

			$dismissible = ( self::is_wpinventory_page() ) ? '' : ' is-dismissible';
			$content .= '<div class="notice notice-error notice-wpinventory' . $dismissible . '" data-notice="plugin-core-required"><p>' . implode( '</p><p>', self::$plugin_errors ) . '</p></div>';
		}

		if ( self::$error ) {
			$content .= '<div class="error"><p><strong>' . self::$error . '</strong></p></div>';
		}

		return $content;
	}

	protected static function output_messages() {
		$content = '';
		if ( self::$message ) {
			$content = '<div class="updated"><p><strong>' . self::$message . '</strong></p></div>';
		}

		return $content;
	}

	protected static function get_inventory_item( $inventory_id ) {
		$item = new WPIMItem();

		return $item->get( $inventory_id );
	}

	protected static function donate_button() {
		if ( self::is_honest_user() ) {
			return '';
		}

		$content = '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="donate" target="_top">';
		$content .= '<input type="hidden" name="cmd" value="_s-xclick">';
		$content .= '<input type="hidden" name="hosted_button_id" value="MQ5ZQYHBB86RQ">';
		$content .= '<input type="image" name="submit" alt="DONATE" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif">';
		$content .= '<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">';
		$content .= '</form>';

		return $content;
	}

	public static function getOptions() {
		return self::$config->get_all();
	}

	public static function getOption( $key, $default = NULL ) {
		return self::$config->get( $key, $default );
	}

	public static function updateOption( $key, $value ) {
		self::$config->set( $key, $value );
	}

	public static function getDisplay( $key ) {
		$key     = self::getDisplayKey( $key );
		$display = explode( ',', self::getOption( $key ) );

		return array_filter( $display );
	}

	public static function getDisplayKey( $key ) {
		return 'display_' . apply_filters( 'wpim_get_display_options_key', $key );
	}

	protected static function get_action() {
		$action = strtolower( self::request( "action" ) );
		$action = str_replace( ' ', '_', $action );

		return $action;
	}

	public static function get_labels() {
		self::load_labels();

		return self::$label->get_all();
	}

	public static function get_label( $field ) {
		self::load_labels();
		$label = self::$label->get( $field );
		if ( is_array( $label ) ) {
			$label = $label['label'];
		}

		return apply_filters( 'wpim_get_label', $label, $field );
	}

	protected static function labels() {
		if ( ! self::$label ) {
			self::$label = WPIMLabel::getInstance();
		}

		return self::$label;
	}

	public static function label( $field ) {
		echo self::get_label( $field );
	}

	protected static function reset_labels() {
		self::load_labels();
		self::$label->reset();
	}

	public static function get_field_from_label( $label ) {
		self::load_labels();

		return self::$label->find_field( $label );
	}

	public static function get_labels_always_on() {
		self::load_labels();

		return self::$label->always_on();
	}

	public static function label_is_on( $field ) {
		self::load_labels();
		$label   = self::$label->get( $field );
		$is_used = ( isset( $label['is_used'] ) ) ? $label['is_used'] : FALSE;

		return apply_filters( 'wpim_label_is_on', $is_used, $field, $label );
	}

	private static function load_labels() {
		if ( ! self::$label ) {
			self::$label = WPIMLabel::getInstance();
		}
	}

	public static function get_statuses() {
		self::load_statuses();

		return self::$status->get_all();
	}

	public static function get_status( $status_id ) {
		self::load_statuses();
		$status = self::$status->get( $status_id );
		if ( is_array( $status ) ) {
			return $status['status_name'];
		}
	}

	protected static function statuses() {
		if ( ! self::$status ) {
			self::$status = WPIMStatus::getInstance();
		}

		return self::$status;
	}

	private static function load_statuses() {
		if ( ! self::$status ) {
			self::$status = WPIMStatus::getInstance();
		}
	}

	protected static function hidden_input( $name, $value, $id = '' ) {
		$input = '<input type="hidden" name="' . $name . '" value="' . $value . '"' . self::addId( $id ) . ' />';

		return $input;
	}

	protected static function text_input_field( $label, $name, $value, $class = "" ) {
		$class   = ( $class ) ? $class : '';
		$content = '<div>
		<label for="' . $name . '">' . $label . '</label>
		<input type="text" name="' . $name . '" class="text ' . $class . '" value="' . htmlentities( $value ) . '" />
		</div>' . PHP_EOL;

		return $content;
	}

	protected static function text_area_input( $label, $name, $value, $rows, $p = 'p', $cols = "", $class = "" ) {
		$class   = ( $class ) ? ' ' . $class : '';
		$p       = ( $p == 'div' ) ? 'div' : 'p';
		$content = '<' . $p . '><label for="' . $name . '">' . $label . '</label><textarea name="' . $name . '" class="area' . $class . '" rows="' . $rows . '" cols="' . $cols . '"/>' . $value . '</textarea></' . $p . '>' . PHP_EOL;

		return $content;
	}

	protected static function email_input_field( $label, $name, $value, $p = 'p', $class = "" ) {
		$class   = ( $class ) ? $class : '';
		$p       = ( $p == 'div' ) ? 'div' : 'p';
		$content = '<' . $p . '><label for="' . $name . '">' . $label . '</label><input type="email" name="' . $name . '" class="text' . $class . '" value="' . htmlentities( $value ) . '" /></' . $p . '>' . PHP_EOL;

		return $content;
	}

	protected static function checkbox_field( $label, $name, $value ) {
		$checked = ( $value ) ? ' checked' : '';
		$content = '<div class="checkbox">
		<label for="' . $name . '">' . $label . '</label>
		<input type="checkbox" id="' . $name . '" name="' . $name . '"' . $checked . '>
		</div>' . PHP_EOL;

		return $content;
	}

	protected static function radio_button( $inputName, $inputValue, $inputChecked = "", $inputClass = "", $id = '' ) {
		$temp_input = '<input type="radio" id="' . $id . '" name="' . $inputName . '" value="' . $inputValue . '" ';
		if ( $inputChecked != "" && $inputChecked != "no" ) {
			$temp_input .= 'checked ="checked"';
		}
		$temp_input .= ( $inputClass ) ? 'class="' . $inputClass . '"' : '';

		$temp_input .= ">";
		$temp_input .= LF;

		return $temp_input;
	}

	protected static function addClass( $class ) {
		$temp_class = "";
		if ( $class != "" ) {
			$temp_class .= ' class="' . $class . '"';
		}

		return $temp_class;
	}

	protected static function addID( $id ) {
		$temp_class = "";
		if ( $id != "" ) {
			$temp_class .= ' id="' . $id . '"';
		}

		return $temp_class;
	}

	protected static function active_class( $selected, $this_one, $class = 'active' ) {
		return ( $selected == $this_one ) ? $class : '';
	}

	protected static function page_id_field( $label, $name, $value, $none_page = 'Select Page...' ) {
		echo '<div><label for="' . $name . '">' . $label . '</label>' . PHP_EOL;
		wp_dropdown_pages( 'name=' . $name . '&selected=' . $value . '&show_option_none=' . $none_page );
		echo '</div>' . PHP_EOL;
	}

	protected static function wp_editor_field( $label, $name, $value ) {
		echo '<div class="editor"><label for="' . $name . '">' . $label . '</label>';
		echo wp_editor( $value, $name, array(
			'textarea_name' => $name,
			'textarea_rows' => self::$editor_rows,
			'media_buttons' => FALSE,
			'teeny'         => self::$teeny
		) );
		echo '</div>' . PHP_EOL;
	}

	protected static function dropdown_field( $label, $name, $value, $table, $idfield, $textfield, $where = NULL, $groupby = NULL ) {
		$groupby = ( $groupby == NULL ) ? '' : ' GROUP BY ' . $groupby;
		$where   = ( $where == NULL ) ? '' : ' WHERE ' . $where;
		$content = '<div><label for="' . $name . '">' . $label . '</label>';
		$content .= '<select name="' . $name . '">' . PHP_EOL;
		$content .= '<option value="">Select...</option>' . PHP_EOL;
		$options = self::$wpdb->get_results( "SELECT " . $idfield . ", " . $textfield . " FROM " . $table . $where . $groupby . " ORDER BY " . $textfield );
		foreach ( $options as $option ) {
			$content .= '<option value="' . $option->$idfield . '"';
			$content .= ( $option->$idfield == $value ) ? ' selected' : '';
			$content .= '>' . $option->$textfield . '</option>' . PHP_EOL;
		}
		$content .= '</select>' . PHP_EOL;
		$content .= '</div>' . PHP_EOL;

		return $content;
	}

	protected static function status_field( $label, $name, $value ) {
		$statii = array(
			'0' => 'Active',
			'1' => 'Inactive'
		);

		return self::array_dropdown( $label, $name, $value, $statii );
	}

	public static function dropdown_array( $name, $selected, $array, $class = '', $atts = '' ) {
		if ( $atts ) {
			$atts = ' ' . $atts;
		}

		$content = '<select name="' . $name . '" class="' . $class . '"' . $atts . '>' . PHP_EOL;;
		foreach ( $array as $key => $value ) {
			$content .= '<option value="' . $key . '"';
			$content .= ( $key == $selected ) ? ' selected' : '';
			$content .= '>' . $value . '</option>' . PHP_EOL;
		}
		$content .= '</select>' . PHP_EOL;

		return $content;
	}

	public static function dropdown_yesno( $name, $selected, $class = '' ) {
		$array = array(
			'0' => self::__( 'No' ),
			'1' => self::__( 'Yes' )
		);

		return self::dropdown_array( $name, $selected, $array, $class );
	}

	protected static function dropdown_required( $name, $selected, $class = '' ) {
		$array = array(
			'0' => self::__( 'Do Not Display' ),
			'1' => self::__( 'Display (not required)' ),
			'2' => self::__( 'Required' )
		);

		return self::dropdown_array( $name, $selected, $array, $class );
	}

	protected static function dropdown_date_format( $name, $selected, $class = '' ) {
		$array = array(
			'n/j/y'      => '1/9/13',
			'm/d/y'      => '01/09/13',
			'n/j/Y'      => '1/9/2013',
			'm/d/Y'      => '01/09/2013',
			'M j, Y'     => 'Jan 9, 2013',
			'M jS, Y'    => 'Jan 9th, 2013',
			'F j, Y'     => 'January 9, 2013',
			'F jS, Y'    => 'January 9th, 2013',
			'D, M j, Y'  => 'Wed, Jan 9, 2013',
			'D, M jS, Y' => 'Wed, Jan 9th, 2013',
			'l, M j, Y'  => 'Wednesday, Jan 9, 2013',
			'l, M jS, Y' => 'Wednesday, Jan 9th, 2013',
			'D, F j, Y'  => 'Wed, January 9, 2013',
			'D, F jS, Y' => 'Wed, January 9th, 2013',
			'l, F j, Y'  => 'Wednesday, January 9, 2013',
			'l, F jS, Y' => 'Wednesday, January 9th, 2013',
			'Y-m-d'      => '2013-11-24',
		);

		return self::dropdown_array( $name, $selected, $array, $class );
	}

	protected static function date_input_field( $label, $name, $value, $class = "", $p = 'p' ) {
		$date    = self::date( $value );
		$class   = ( $class ) ? ' ' . $class : '';
		$p       = ( $p == 'div' ) ? 'div' : 'p';
		$content = '<' . $p . '><label for="' . $name . '">' . $label . '</label>' . PHP_EOL;
		$content .= '<input type="text" name="' . $name . '" class="date datepicker' . $class . '" value="' . $date . '">' . PHP_EOL;
		$content .= '</' . $p . '>' . PHP_EOL;

		return $content;
	}

	protected static function time_input_field( $label, $name, $value, $class = "" ) {
		if ( ! is_numeric( $value ) ) {
			$value = strtotime( $value );
		}
		$content = '<div class="time ' . $class . '>';
		$content .= '<label for="' . $name . 'hour">' . $label . '</label>';
		$content .= self::hours_input_field( $name . 'hour', $value );
		$content .= self::minutes_input_field( $name . 'minute', $value );
		$content .= self::ampm_input_field( $name . '_am', $value );
		$content .= '</div>';

		return $content;
	}

//	protected static function make_api_call($method, $data = NULL, $force = FALSE) {
//		return self::$api->make_call($method, $data, $force);
//	}

	public static function get_reg_info() {
		$reg_info = get_option( 'wpim_license' );

		if ( $reg_info && ! isset( $reg_info['core'] ) ) {
			$reg_info = array( 'core' => $reg_info );
			update_option( 'wpim_license', $reg_info );
		}

		return $reg_info;
	}

	/**
	 * HOW'S IT GOING?
	 * If you find yourself here, please contact us.  We've worked
	 * hard to make this plugin reasonably priced, and if you feel
	 * the need to hack this, then we'd love the opportunity to
	 * hear from you first.
	 */
	protected static function is_honest_user() {

		$reg_info = self::get_reg_info();
		$reg_info = ( isset( $reg_info['core'] ) ) ? $reg_info['core'] : FALSE;
		if ( empty( $reg_info ) ) {
			return FALSE;
		}

		if ( empty( $reg_info['key'] ) || empty( $reg_info['valid'] ) ) {
			return FALSE;
		}

		if ( empty( $reg_info['expires'] ) || $reg_info['expires'] < time() ) {
			return FALSE;
		}

		return TRUE;
	}

	protected static function validate( $add_on ) {
		if ( empty( self::$add_ons ) ) {
			self::$add_ons = self::get_add_ons();
		}

		$found = FALSE;
		foreach ( (array) self::$add_ons AS $all_add_on ) {
			if ( $all_add_on->key == $add_on ) {
				$found = $all_add_on;
				break;
			}
		}

		$add_on = ( $found ) ? $found : $add_on;

		$all_reg_info = self::get_reg_info();
		if ( $add_on && is_object( $add_on ) && ! empty( $all_reg_info[ $add_on->key ] ) ) {
			$reg_info = $all_reg_info[ $add_on->key ];

			if ( ! empty( $reg_info['expires'] ) && ( $reg_info['expires'] > time() ) ) {
				return TRUE;
			}
		}

		if ( $add_on && is_object( $add_on ) ) {
			$add_on = $add_on->item_name;
		}

		$error = sprintf( self::__( '%s license is invalid or not found.' ), $add_on );
		$error .= ' <a href="' . admin_url( 'admin.php?page=wpim_manage_settings' ) . '">' . self::__( 'Enter your license key now.' ) . '</a>';

		self::$plugin_errors[] = $error;

		return FALSE;
	}

	/**
	 * Gets a list of all add-ons.
	 *
	 * @param bool $installed
	 * @param bool $force
	 *
	 * @return array|bool|mixed|object|string|void
	 */
	protected static function get_add_ons( $installed = FALSE, $force = FALSE ) {
		$add_ons = get_transient( 'wpim_add_ons' );

		if ( $force ) {
			$add_ons = FALSE;
		}

		if ( ! $add_ons ) {
			$add_ons = WPIMAPI::make_call( 'get_add_ons' );

			if ( ! $add_ons ) {
				$add_ons = '[{"title":"Ledger","image":"http:\/\/www.wpinventory.com\/wp-content\/themes\/wpinventory\/images\/icons\/inventory_ledger.png","description":"<p>Track additions and subtractions to your inventory <strong>with ease!<\/strong>","learn_more_url":"https:\/\/www.wpinventory.com\/downloads\/wp-inventory-ledger\/","key":"ledger","item_name":"Add-On: Ledger"},{"title":"Import \/ Export","image":"http:\/\/www.wpinventory.com\/wp-content\/themes\/wpinventory\/images\/icons\/import_export.png","description":"<p>Import CSV files to your inventory, and export your inventory at any time.<\/p>","learn_more_url":"https:\/\/www.wpinventory.com\/downloads\/wp-inventory-import-and-export\/","key":"import_export","item_name":"Add-On: Import and Export"},{"title":"Advanced User Control","image":"http:\/\/www.wpinventory.com\/wp-content\/themes\/wpinventory\/images\/icons\/advanced_user_control.png","description":"<p>Provides detailed control over each user and their permissions for inventory items.<\/p>","learn_more_url":"https:\/\/www.wpinventory.com\/downloads\/advanced-user-control\/","key":"advanced_user","item_name":"Add-On: Advanced User Control"},{"title":"Bulk Item Manager","image":"http:\/\/www.wpinventory.com\/wp-content\/themes\/wpinventory\/images\/icons\/bulk_item_manager.png","description":"<p>Powerful tool for deleting and updating items in bulk.  Select based on a variety of criteria, preview the changes, and more.<\/p>","learn_more_url":"https:\/\/www.wpinventory.com\/downloads\/add-on-bulk-item-manager\/","key":"bulk_item","item_name":"Add-On: Bulk Item Manager"},{"title":"Advanced Inventory Manager","image":"http:\/\/www.wpinventory.com\/wp-content\/themes\/wpinventory\/images\/icons\/advanced_inventory_manager.png","description":"<p>Add more fields, manage the kinds of fields (including drop-downs, radio buttons, and more), support different types of inventory, and more.<\/p>","learn_more_url":"https:\/\/www.wpinventory.com\/downloads\/add-on-advanced-inventory-manager\/","key":"advanced_inventory","item_name":"Add-On: Advanced Inventory Manager"}]';
				$add_ons = json_decode( $add_ons );
			}

			set_transient( 'wpim_add_ons', $add_ons, 12 * HOUR_IN_SECONDS );
		}

		$add_ons = apply_filters( 'wpim_add_ons_list', $add_ons );

		if ( ! $installed ) {
			return $add_ons;
		}

		$new_add_ons = array();
		foreach ( (array) $add_ons AS $add_on ) {
			if ( isset( $add_on->key ) ) {
				$new_add_ons[ $add_on->key ] = $add_on;
			}
		}

		if ( $installed ) {
			foreach ( $new_add_ons AS $key => $add_on ) {
				if ( empty( $add_on->installed ) ) {
					unset( $new_add_ons[ $key ] );
				}
			}
		}

		return $new_add_ons;
	}

	/**
	 * Checks if an add-on is installed and activated.
	 * Utilizes the add_on->key as the key. ('import_export', 'advanced_inventory', etc).
	 *
	 * @param string $add_on
	 *
	 * @return bool
	 */
	public static function is_add_on_installed( $add_on ) {
		$installed = self::get_add_ons( TRUE );

		foreach ( (array) $installed AS $installed_add_on ) {
			if ( $add_on == $installed_add_on->key ) {
				return ( ! empty( $installed_add_on->version ) ) ? $installed_add_on->version : TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Utility function to create the appropriate media upload fields
	 */
	protected static function media_field( $label, $name, $count, $type = "image", $src = "" ) {
		$content = '<div class="media-upload">' . PHP_EOL;
		$content .= '<label for="' . $name . '">' . $label . '</label>' . PHP_EOL;
		$word  = ( $src ) ? 'Change' : 'Add New';
		$ftype = ( $type == "image" ) ? "hidden" : "text";
		$content .= '<input type="' . $ftype . '" name="' . $name . '" value="' . $src . '" data-count="' . $count . '" id="media-field-' . $count . '" class="image-upload" />' . PHP_EOL;
		$content .= '<div class="imagewrapper">';
		if ( $type == "image" ) {
			$content .= '<div class="imagecontainer" id="media-div-' . $count . '">';
			if ( $src ) {
				$content .= '<img class="image-upload" id="media-image-' . $count . '" src="' . $src . '" />';
				$content .= '<a href="javascript:removeImage(' . $count . ');" class="delete" id="media-delete-' . $count . '" title="Click to remove image">X</a>' . PHP_EOL;
			}
			$content .= '</div>' . PHP_EOL;
		}
		$content .= '<a href="media-upload.php?post_id=0&type=image&TB_iframe=1&width=640&height=673" data-count="' . $count . '" id="media-link-' . $count . '" class="image-upload button">' . $word . ' ' . ucwords( $type ) . '</a>' . PHP_EOL;
		$content .= '</div>' . PHP_EOL;
		$content .= '</div>' . PHP_EOL;

		return $content;
	}

	protected static function date( $date ) {
		if ( ! $date ) {
			return '';
		}
		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}
		if ( ! self::$date_format ) {
			self::$date_format = self::$config->get( 'date_format' );
		}

		return date( self::$date_format, $date );
	}

	protected static function time( $date ) {
		if ( ! $date ) {
			return '';
		}
		if ( ! is_numeric( $date ) ) {
			$date = strtotime( $date );
		}

		return date( 'g:i:a', $date );
	}

	protected static function get_checkbox( $key ) {
		return ( isset( $_POST[ $key ] ) ) ? 1 : 0;
	}

	/**
	 * Returns whether a user is authorized to edit an inventory item
	 *
	 * @param int $inventory_id
	 *
	 * @return bool
	 */
	protected static function user_can_edit( $inventory_id = NULL ) {
		return WPIMAdmin::check_permission( 'edit_item', $inventory_id );
	}

	public static function prep_sort( $default = NULL, $default_dir = NULL ) {
		self::$sortby  = self::request( 'sortby', $default );
		self::$sortdir = self::request( 'sortdir', $default_dir );
	}

	/**
	 * Verifies that the logged in user is authorized to edit the item.
	 *
	 * @param string         $type
	 * @param object|integer $inventory_id - either the inventory item object or the id of the inventory item
	 *
	 * @return bool
	 */
	protected static function check_permission( $type, $inventory_id ) {

		$inventory_item = NULL;

		// Can accept either an object or an integer.  Load the item if is not the inventory object
		if ( is_scalar( $inventory_id ) ) {
			$inventory_item = self::get_inventory_item( $inventory_id );
		}

		if ( ! get_current_user_id() ) {
			return FALSE;
		}

		if ( $type == 'edit_item' || $type == 'save_item' || $type == 'add_item' ) {
			$restrict = self::$config->get( 'permissions_user_restricted' );
			if ( (int) $restrict == 2 ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					$user_id = get_current_user_id();
					if ( $inventory_item->user_id && ! ( (int) $inventory_item->user_id === (int) $user_id ) ) {
						return FALSE;
					}
				}
			}
		}

		return apply_filters( 'wpim_check_permission', TRUE, $type, $inventory_item );
	}

	/**
	 * Sets up the query args to ensure the current user has permission to view the items.
	 * Must be called manually.  Should not be used on the front-end.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	protected static function permission_args( $args = array() ) {
		if ( is_admin() ) {
			$restrict = self::$config->get( 'permissions_user_restricted' );
			if ( (int) $restrict == 2 ) {
				if ( ! current_user_can( 'manage_options' ) ) {
					$args['user_id'] = get_current_user_id();
				}
			}
		}

		return $args;
	}

	protected static function load_available_themes() {
		$theme_path      = self::$path . self::$theme_path;
		$theme_url       = self::$url . self::$theme_path;
		$screenshot_path = str_replace( '/css/', '/screenshots/', $theme_path );
		$screenshot_url  = str_replace( '/css/', '/screenshots/', $theme_url );
		$glob_path       = $theme_path . '*.css';

		$theme_files = glob( $glob_path );

		$themes = array();

		foreach ( $theme_files AS $file ) {
			$theme_name                                                = str_replace( array(
				$theme_path,
				'.css'
			), '', $file );
			$screenshot                                                = ( file_exists( $screenshot_path . $theme_name . '.jpg' ) ) ? $screenshot_url . $theme_name . '.jpg' : '';
			$themes[ ucwords( str_replace( '-', ' ', $theme_name ) ) ] = array(
				'css'        => self::get_theme_url( $file ),
				'screenshot' => $screenshot
			);
		}

		return $themes;
	}

	protected static function get_theme_url( $theme ) {
		$theme = strtolower( str_replace( ' ', '-', $theme ) );
		if ( stripos( $theme, '.css' ) === FALSE ) {
			$theme .= '.css';
		}

		if ( stripos( $theme, '/' ) !== FALSE ) {
			$theme = explode( '/', $theme );
			$theme = end( $theme );
		}

		return self::$url . self::$theme_path . $theme;
	}

	public static function debug_log( $string ) {
		$where = debug_backtrace();
		$where = $where[1];
		if ( class_exists( 'WPIMLogging' ) ) {
			WPIMLogging::log( $string, $where );
		}
	}

	public static function admin_notices() {
		if ( self::$notices ) {
			echo self::$notices;
		}
	}

	/**
	 * Utility function to detect if we are on a "WP Inventory page"
	 *
	 * @param string $check - the specific page to check, if desired
	 *
	 * @return bool
	 */
	public static function is_wpinventory_page( $check = '' ) {
		$page = ( isset( $_GET["page"] ) ) ? $_GET["page"] : '';

		if ( ! $check ) {
			return ( in_array( $page, self::$pages ) ) ? TRUE : FALSE;
		} else {
			return ( $page == $check ) ? TRUE : FALSE;
		}
	}

	/**
	 * Getter / setter for storing the state of dismissed notices.
	 *
	 * @param string    $type
	 * @param bool|NULL $dismissed
	 *
	 * @return mixed|void
	 */
	public static function notice_dismissed( $type, $dismissed = NULL ) {
		if ( NULL === $dismissed ) {
			return self::$config->get( 'dismissed-' . $type, FALSE );
		} else {
			self::$config->set( 'dismissed-' . $type, $dismissed );
		}
	}


	public static function dump( $var ) {
		echo '<pre>';
		var_dump( $var );
		echo '</pre>';
	}
}
