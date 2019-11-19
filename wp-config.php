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
define('DB_NAME', 'justgra4_WP9ZB');

/** MySQL database username */
define('DB_USER', 'justgra4_WP9ZB');

/** MySQL database password */
define('DB_PASSWORD', 'W9i5DdOHPsHccoyTi');

/** MySQL hostname */
define('DB_HOST', 'localhost');

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
define('AUTH_KEY', '7e7dcbfd0c148749f2e794a9ee168b0f53b09f696d1efd1f7e12ac3dbc0c25b0');
define('SECURE_AUTH_KEY', 'a6f5f167158f25e3ac642ee0554f86d337d1aa61d4f7647617d1f5887ee7aa08');
define('LOGGED_IN_KEY', '150493c2fd90e7418203a2ed8b3eecbe269b75a11735c47570bf1bc2cabb21ae');
define('NONCE_KEY', '79fe45a06715af8ffa388c3c10f26248d0c7e2f2724f08e482b34e4fc8e651d1');
define('AUTH_SALT', 'd1d3398bad6c6e415a832266cd066906bc9870144bf2b6a036c2d7f4058e56c9');
define('SECURE_AUTH_SALT', '1614660963cacd722f5e51bab7ef0a36522d64ca552b7beda7f1b295d8a87f21');
define('LOGGED_IN_SALT', '70d524a0b25d3ec67727f5db04b73e486d1ab01089f8439adc5d79684b8f3171');
define('NONCE_SALT', '382db2baa25a54c8eadd01342a52faf9983d5ea9dfce664b48a34bd1d7598bce');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = '_9ZB_';

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


// Settings modified by hosting provider
define( 'WP_CRON_LOCK_TIMEOUT', 120   );
define( 'AUTOSAVE_INTERVAL',    300   );
define( 'WP_POST_REVISIONS',    5     );
define( 'EMPTY_TRASH_DAYS',     7     );
define( 'WP_AUTO_UPDATE_CORE',  true  );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
