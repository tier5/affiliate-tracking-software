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
define('DB_NAME', 'reviewvelocity');

/** MySQL database username */
define('DB_USER', 'reviewvelocity');

/** MySQL database password */
define('DB_PASSWORD', 'GFSGBsd456#456dD');

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
define('AUTH_KEY',         '~-n.+:q*M$W}vPgZCu[D9WZ&G|[u~Y76~4dR/!uBd=^t&YQ8@@:nqzz3.R*do[~W');
define('SECURE_AUTH_KEY',  '4dM:~poVGP]m-Y>V&Ew52xb<Oop@;-1PYj[6vz&Jqa[}S_=+OX5*(jsG>]KfC^xv');
define('LOGGED_IN_KEY',    'lH!@{D*v?i5Lye052mY,D@Sd$)+8Z4|o2bd4>p4+m!qbU(b4`9dr5vM8r_CC5kxq');
define('NONCE_KEY',        '8g9OG|[IEV]*F#$VF*/]Luxl68*WzE|/^raovd`ka!Kn])|8uDocEe.~Xpq!9$_q');
define('AUTH_SALT',        '}ca@|p<M=0||E_ZHFFgf+Hqf9vJ772J:jMttZo<PYzpoy!Ec_*-eLba[|OxwYOtp');
define('SECURE_AUTH_SALT', '#Dy0(Rt!b%&I%{)qz75>o!|C}#Ap_8>@#?a=}UtF3USO-1FTiE|T.Yyp|RIyWn,Z');
define('LOGGED_IN_SALT',   '?mp1Aey%MRIw*$Y/!g<DGjyDx!v{B |Z/YlHLH-!CaKHi ,:4Q~912WGUw-/Z=FM');
define('NONCE_SALT',       '#2SQ<-lvtR2Z3Jj}/t{h.%lvLUR5?3]gH$+ta-y{q)BUK&K1W D-e_){/>T|l P?');

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
