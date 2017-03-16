<?php
/**
 * Internationalization helper.
 *
 * @package     Kirki
 * @category    Core
 * @author      Aristeides Stathopoulos
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       1.0
 */

if ( ! class_exists( 'Kirki_l10n' ) ) {

	/**
	 * Handles translations
	 */
	class Kirki_l10n {

		/**
		 * The plugin textdomain
		 *
		 * @access protected
		 * @var string
		 */
		protected $textdomain = 'kirki';

		/**
		 * The class constructor.
		 * Adds actions & filters to handle the rest of the methods.
		 *
		 * @access public
		 */
		public function __construct() {

			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );

		}

		/**
		 * Load the plugin textdomain
		 *
		 * @access public
		 */
		public function load_textdomain() {

			if ( null !== $this->get_path() ) {
				load_textdomain( $this->textdomain, $this->get_path() );
			}
			load_plugin_textdomain( $this->textdomain, false, Kirki::$path . '/languages' );

		}

		/**
		 * Gets the path to a translation file.
		 *
		 * @access protected
		 * @return string Absolute path to the translation file.
		 */
		protected function get_path() {
			$path_found = false;
			$found_path = null;
			foreach ( $this->get_paths() as $path ) {
				if ( $path_found ) {
					continue;
				}
				$path = wp_normalize_path( $path );
				if ( file_exists( $path ) ) {
					$path_found = true;
					$found_path = $path;
				}
			}

			return $found_path;

		}

		/**
		 * Returns an array of paths where translation files may be located.
		 *
		 * @access protected
		 * @return array
		 */
		protected function get_paths() {

			return array(
				WP_LANG_DIR . '/' . $this->textdomain . '-' . get_locale() . '.mo',
				Kirki::$path . '/languages/' . $this->textdomain . '-' . get_locale() . '.mo',
			);

		}

		/**
		 * Shortcut method to get the translation strings
		 *
		 * @static
		 * @access public
		 * @param string $config_id The config ID. See Kirki_Config.
		 * @return array
		 */
		public static function get_strings( $config_id = 'global' ) {

			$translation_strings = array(
				'background-color'      => esc_attr__( 'Background Color', 'wp-simple' ),
				'background-image'      => esc_attr__( 'Background Image', 'wp-simple' ),
				'no-repeat'             => esc_attr__( 'No Repeat', 'wp-simple' ),
				'repeat-all'            => esc_attr__( 'Repeat All', 'wp-simple' ),
				'repeat-x'              => esc_attr__( 'Repeat Horizontally', 'wp-simple' ),
				'repeat-y'              => esc_attr__( 'Repeat Vertically', 'wp-simple' ),
				'inherit'               => esc_attr__( 'Inherit', 'wp-simple' ),
				'background-repeat'     => esc_attr__( 'Background Repeat', 'wp-simple' ),
				'cover'                 => esc_attr__( 'Cover', 'wp-simple' ),
				'contain'               => esc_attr__( 'Contain', 'wp-simple' ),
				'background-size'       => esc_attr__( 'Background Size', 'wp-simple' ),
				'fixed'                 => esc_attr__( 'Fixed', 'wp-simple' ),
				'scroll'                => esc_attr__( 'Scroll', 'wp-simple' ),
				'background-attachment' => esc_attr__( 'Background Attachment', 'wp-simple' ),
				'left-top'              => esc_attr__( 'Left Top', 'wp-simple' ),
				'left-center'           => esc_attr__( 'Left Center', 'wp-simple' ),
				'left-bottom'           => esc_attr__( 'Left Bottom', 'wp-simple' ),
				'right-top'             => esc_attr__( 'Right Top', 'wp-simple' ),
				'right-center'          => esc_attr__( 'Right Center', 'wp-simple' ),
				'right-bottom'          => esc_attr__( 'Right Bottom', 'wp-simple' ),
				'center-top'            => esc_attr__( 'Center Top', 'wp-simple' ),
				'center-center'         => esc_attr__( 'Center Center', 'wp-simple' ),
				'center-bottom'         => esc_attr__( 'Center Bottom', 'wp-simple' ),
				'background-position'   => esc_attr__( 'Background Position', 'wp-simple' ),
				'background-opacity'    => esc_attr__( 'Background Opacity', 'wp-simple' ),
				'on'                    => esc_attr__( 'ON', 'wp-simple' ),
				'off'                   => esc_attr__( 'OFF', 'wp-simple' ),
				'all'                   => esc_attr__( 'All', 'wp-simple' ),
				'cyrillic'              => esc_attr__( 'Cyrillic', 'wp-simple' ),
				'cyrillic-ext'          => esc_attr__( 'Cyrillic Extended', 'wp-simple' ),
				'devanagari'            => esc_attr__( 'Devanagari', 'wp-simple' ),
				'greek'                 => esc_attr__( 'Greek', 'wp-simple' ),
				'greek-ext'             => esc_attr__( 'Greek Extended', 'wp-simple' ),
				'khmer'                 => esc_attr__( 'Khmer', 'wp-simple' ),
				'latin'                 => esc_attr__( 'Latin', 'wp-simple' ),
				'latin-ext'             => esc_attr__( 'Latin Extended', 'wp-simple' ),
				'vietnamese'            => esc_attr__( 'Vietnamese', 'wp-simple' ),
				'hebrew'                => esc_attr__( 'Hebrew', 'wp-simple' ),
				'arabic'                => esc_attr__( 'Arabic', 'wp-simple' ),
				'bengali'               => esc_attr__( 'Bengali', 'wp-simple' ),
				'gujarati'              => esc_attr__( 'Gujarati', 'wp-simple' ),
				'tamil'                 => esc_attr__( 'Tamil', 'wp-simple' ),
				'telugu'                => esc_attr__( 'Telugu', 'wp-simple' ),
				'thai'                  => esc_attr__( 'Thai', 'wp-simple' ),
				'serif'                 => _x( 'Serif', 'font style', 'wp-simple' ),
				'sans-serif'            => _x( 'Sans Serif', 'font style', 'wp-simple' ),
				'monospace'             => _x( 'Monospace', 'font style', 'wp-simple' ),
				'font-family'           => esc_attr__( 'Font Family', 'wp-simple' ),
				'font-size'             => esc_attr__( 'Font Size', 'wp-simple' ),
				'font-weight'           => esc_attr__( 'Font Weight', 'wp-simple' ),
				'line-height'           => esc_attr__( 'Line Height', 'wp-simple' ),
				'font-style'            => esc_attr__( 'Font Style', 'wp-simple' ),
				'letter-spacing'        => esc_attr__( 'Letter Spacing', 'wp-simple' ),
				'top'                   => esc_attr__( 'Top', 'wp-simple' ),
				'bottom'                => esc_attr__( 'Bottom', 'wp-simple' ),
				'left'                  => esc_attr__( 'Left', 'wp-simple' ),
				'right'                 => esc_attr__( 'Right', 'wp-simple' ),
				'center'                => esc_attr__( 'Center', 'wp-simple' ),
				'justify'               => esc_attr__( 'Justify', 'wp-simple' ),
				'color'                 => esc_attr__( 'Color', 'wp-simple' ),
				'add-image'             => esc_attr__( 'Add Image', 'wp-simple' ),
				'change-image'          => esc_attr__( 'Change Image', 'wp-simple' ),
				'no-image-selected'     => esc_attr__( 'No Image Selected', 'wp-simple' ),
				'add-file'              => esc_attr__( 'Add File', 'wp-simple' ),
				'change-file'           => esc_attr__( 'Change File', 'wp-simple' ),
				'no-file-selected'      => esc_attr__( 'No File Selected', 'wp-simple' ),
				'remove'                => esc_attr__( 'Remove', 'wp-simple' ),
				'select-font-family'    => esc_attr__( 'Select a font-family', 'wp-simple' ),
				'variant'               => esc_attr__( 'Variant', 'wp-simple' ),
				'subsets'               => esc_attr__( 'Subset', 'wp-simple' ),
				'size'                  => esc_attr__( 'Size', 'wp-simple' ),
				'height'                => esc_attr__( 'Height', 'wp-simple' ),
				'spacing'               => esc_attr__( 'Spacing', 'wp-simple' ),
				'ultra-light'           => esc_attr__( 'Ultra-Light 100', 'wp-simple' ),
				'ultra-light-italic'    => esc_attr__( 'Ultra-Light 100 Italic', 'wp-simple' ),
				'light'                 => esc_attr__( 'Light 200', 'wp-simple' ),
				'light-italic'          => esc_attr__( 'Light 200 Italic', 'wp-simple' ),
				'book'                  => esc_attr__( 'Book 300', 'wp-simple' ),
				'book-italic'           => esc_attr__( 'Book 300 Italic', 'wp-simple' ),
				'regular'               => esc_attr__( 'Normal 400', 'wp-simple' ),
				'italic'                => esc_attr__( 'Normal 400 Italic', 'wp-simple' ),
				'medium'                => esc_attr__( 'Medium 500', 'wp-simple' ),
				'medium-italic'         => esc_attr__( 'Medium 500 Italic', 'wp-simple' ),
				'semi-bold'             => esc_attr__( 'Semi-Bold 600', 'wp-simple' ),
				'semi-bold-italic'      => esc_attr__( 'Semi-Bold 600 Italic', 'wp-simple' ),
				'bold'                  => esc_attr__( 'Bold 700', 'wp-simple' ),
				'bold-italic'           => esc_attr__( 'Bold 700 Italic', 'wp-simple' ),
				'extra-bold'            => esc_attr__( 'Extra-Bold 800', 'wp-simple' ),
				'extra-bold-italic'     => esc_attr__( 'Extra-Bold 800 Italic', 'wp-simple' ),
				'ultra-bold'            => esc_attr__( 'Ultra-Bold 900', 'wp-simple' ),
				'ultra-bold-italic'     => esc_attr__( 'Ultra-Bold 900 Italic', 'wp-simple' ),
				'invalid-value'         => esc_attr__( 'Invalid Value', 'wp-simple' ),
				'add-new'           	=> esc_attr__( 'Add new', 'wp-simple' ),
				'row'           		=> esc_attr__( 'row', 'wp-simple' ),
				'limit-rows'            => esc_attr__( 'Limit: %s rows', 'wp-simple' ),
				'open-section'          => esc_attr__( 'Press return or enter to open this section', 'wp-simple' ),
				'back'                  => esc_attr__( 'Back', 'wp-simple' ),
				'reset-with-icon'       => sprintf( esc_attr__( '%s Reset', 'wp-simple' ), '<span class="dashicons dashicons-image-rotate"></span>' ),
				'text-align'            => esc_attr__( 'Text Align', 'wp-simple' ),
				'text-transform'        => esc_attr__( 'Text Transform', 'wp-simple' ),
				'none'                  => esc_attr__( 'None', 'wp-simple' ),
				'capitalize'            => esc_attr__( 'Capitalize', 'wp-simple' ),
				'uppercase'             => esc_attr__( 'Uppercase', 'wp-simple' ),
				'lowercase'             => esc_attr__( 'Lowercase', 'wp-simple' ),
				'initial'               => esc_attr__( 'Initial', 'wp-simple' ),
				'select-page'           => esc_attr__( 'Select a Page', 'wp-simple' ),
				'open-editor'           => esc_attr__( 'Open Editor', 'wp-simple' ),
				'close-editor'          => esc_attr__( 'Close Editor', 'wp-simple' ),
				'switch-editor'         => esc_attr__( 'Switch Editor', 'wp-simple' ),
			);

			$config = apply_filters( 'kirki/config', array() );

			if ( isset( $config['i18n'] ) ) {
				$translation_strings = wp_parse_args( $config['i18n'], $translation_strings );
			}

			return apply_filters( 'kirki/' . $config_id . '/l10n', $translation_strings );

		}
	}
}
