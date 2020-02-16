<?php
define('WP_CACHE', true); // Added by WP Rocket

define('FS_METHOD', 'direct');

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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db790843712' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'feav' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'U{@58$,n2:T/9-v,QYS!nUB>SE8TrRLHh-txha0C^Qnl5yC+hO`+^H`G@v-l>jgX');
define('SECURE_AUTH_KEY',  'HI}BZ%xYygJyn-X:+M3=-njHII_(,8y=%i]5`wrPdo:+fF*9]<),wK;Dx(Ecw4v^');
define('LOGGED_IN_KEY',    'craY_MgaJ|]8 +f5pe7J9o6Hz6Gox6W!0M$?vv9TU?l@p;)J3AJ1Xu4S1!h;Ygj-');
define('NONCE_KEY',        'B/9:RaU`++kp(Reuf+;/^}ET@?,/e_46]s#FkMLFA9-Fi_~m%Z}Q:H9O)g>@J&cG');
define('AUTH_SALT',        ' jRRFVI3(gJ@&79bs&x>k0L-Za2jSCH6jMP*Lj7JuMHzVBb;zha>/CS+k&#,3 (V');
define('SECURE_AUTH_SALT', '?qZ)YX r.&n:o7DA|fql0*PV:[v0cf_p?6`gHn^>dH=xyh)7F3S$87e*b?OE$R-v');
define('LOGGED_IN_SALT',   'Xw|g/Op1Q+nN:-l$~Y6OI}uE1DjHFspIJT<YL&r4^fEL;1B~*>L7xl%k/,sJs^lV');
define('NONCE_SALT',       'UW^LwjKvjHui+!H+yD+7TvzZE1;L>P-;l*$f3!$(@A0F49:k<X{_Ys+|,-?Yra-T');


/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'QuvCs';



define('WP_MEMORY_LIMIT', '128M');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
