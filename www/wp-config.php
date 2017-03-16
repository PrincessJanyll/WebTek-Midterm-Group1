<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'Natural_Science');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'puU<.{0U7,q;t&KvqIVuaz-q!yju.N2J#Mqz+=f,yc~UeKuNa~;I9lY-(ru*{oo=');
define('SECURE_AUTH_KEY',  'PW{Au!tn F{ZG^N-(*2ow>ap.]wp[p3b}`/l$p,vfPOTyHfIHI94I>r3bk.4FKX%');
define('LOGGED_IN_KEY',    '6{ O5)SUTPy=Q;7%,,^}kg~-38^]=xCr}40&*r?nDx0|x|zv<<?$=3HAJT=@$s%)');
define('NONCE_KEY',        'pQlCi.Ah3.edtx{y5xR!u3e2?F3mW][)E8!%.IUw#%1osIjgqEXRr;^eWL>hh38H');
define('AUTH_SALT',        'Lh>nJxwN;ouT7 U/@b)i`<Rqu(,h-R~%k6q~{f?eidDVi];PL486XD7I.?S:}#vs');
define('SECURE_AUTH_SALT', '&#R_KC~S[eCNCg&R7!2NBUrM7*=},xbBg&rUSByUlR{lzY9YSytA@i=CsP692H)]');
define('LOGGED_IN_SALT',   'PuZ2r|!<`/N&3>0aKIX}6fG}`H[gz&9mfDm3@;O=[Zc~?C-,h>Zns MfWUUBGV$g');
define('NONCE_SALT',       'cMz){V2#7b}BqdXFD_4H`sF|;85wxcIP^E45(&.TI.j(|$CK^.193sMoD::S2C<&');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
