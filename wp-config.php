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
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'wordpress');

/** MySQL database password */
define('DB_PASSWORD', 'wordpress');

/** MySQL hostname */
// define('DB_HOST', 'localhost:32772');
define('DB_HOST', 'database');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'Dd;-;yTejl|AUX|p4R^tG>u%AIHZ$F:8]ewsB/2%_E.|j-xs.4^9HQYp+WE+dY6E');
define('SECURE_AUTH_KEY',  '(bGHKbxziSs^H%PDRyA;yHs9Fsn9>_1A[!,eifoJwHjiK;Z5!x!{*]hs+c-9YqyA');
define('LOGGED_IN_KEY',    ']QI*T]i!3@D9~O{*)JKZ{dOl+vK:KxMOrlMX!gDq o!]<npgDR3)l17tg`gR+`:q');
define('NONCE_KEY',        'F&Jm/.H2>DpP|J{+J*n{k49I!i,6t/c3b<5=BC, *?DATLZmq^Ah+K-pq |0r!)j');
define('AUTH_SALT',        'YB:rEfKy6!:)cK>R_}P$+e?sv7]~K%Y+Xr`#faS!:{rlxy/Hx4/q[##MrC^AB^@/');
define('SECURE_AUTH_SALT', '8DdAE;!PP$OWcvJ0;HMe6.z#-Y):EC2lx6#BY|}iQm?grWyij9t3:CPo5svu[TBe');
define('LOGGED_IN_SALT',   'ini;]xuH+5G#XH7(@X3|<XqgX(@eO?}VJ- r*)?)[-uv2.w=eEE]~=d}_%SySf <');
define('NONCE_SALT',       'P>j@8|%T)t?i7~01z-/?M>J$^WqpM$H|<aj^!(J2lt~p{OQTF5DeuJ1j|n(r3p-N');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
  define('ABSPATH', dirname(__FILE__) . '/');
}

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
