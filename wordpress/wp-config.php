<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'O7lb^v vk4Y7)oScvA9T$2c%n:w[I7Q2T{6 >d&ZK8hJO7M&5vRM~0M]M*Y?cH?B' );
define( 'SECURE_AUTH_KEY',  'L!Jhq:J0*Eqzt!qkkJP4W#H.Z2AoD  pNVgRAn_NjjI2!C>tt,gT*.)fT?d+upW^' );
define( 'LOGGED_IN_KEY',    'UO+Eh:D`!/^@DOLno4g?`z19!@/0Cr-]CgZeNm}SrAE{E]A@2@?0[ygI%Gb.18;L' );
define( 'NONCE_KEY',        'C{7@lQbCG`%/M}uX/Y~CExb<QPp[fs1si3epQ lmPG*_lE%RR15;*Z-AEFIHzbc^' );
define( 'AUTH_SALT',        '#w!c}M++Q2q{H;.qdBusnw-RgspoK([I2AH0V3sGZ:>%B];faR)tL$%nnRv{VQ.6' );
define( 'SECURE_AUTH_SALT', 'X$3fi~6sX7g-awSp:m~qrxi9pvn@u 4#vQT (+t83t.g!G2` <ZD@t46$oE]*BC=' );
define( 'LOGGED_IN_SALT',   'n%,.iJEb(kjrrBy<b)-/2tQqqEpW;em_1C.CE0(.K5~y<*r`SFYJ6/NDwlA; z&a' );
define( 'NONCE_SALT',       'EbJE1gn??y/XOw_1*<)<8_I?fhoV0WTqMs<fTlH)zaZ?TpsOjF^3-14mDa1TQ36_' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
