<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp38-2');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

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
define('AUTH_KEY',         '==_$*?%h>n#X@K!5ssd~.l7|)7~8*TYzC$.eYk-kKW`+0kD9FB-tBy#|{cSr~0TM');
define('SECURE_AUTH_KEY',  'FS*Yc@//-@qFSsN|hT$j85-%^p5MXVe$Mq#el.DJ9Fy=u:Hw0!|mH?)`+|j>o8{^');
define('LOGGED_IN_KEY',    '`%n?N,Y8:7m4Is `sLMg,Er!qQG-l-dq+_GK!jv-efRct Mm80v%wE*IB8xk*!x&');
define('NONCE_KEY',        '=oph8?&&h-J|YKGD?Wh:j7d2rD]JGa_3h8T*3PtOc,c|L!X`JBGwl_*7sczQc`s?');
define('AUTH_SALT',        '70-TTJFll%tGH;,89QGC[<!%Sz[-/j,A:o{;043G*/^4|u{IbUUtlH2*e~M{lntX');
define('SECURE_AUTH_SALT', 'sAk(cNqVi[w-up-Rj:Z.D}2-jQO|QAXwjR9#(m7iw3-Di}^|xb:S LKn!Djs*dca');
define('LOGGED_IN_SALT',   '&6U[4-Wgf; @5y#?qCUS+FGPZ-67CS{x>X(n)S-Qg-DgVvPx6SPqMMKU#)*,!kGf');
define('NONCE_SALT',       '.QyFIQ14G1?VWm`6u=#+M1~qZgG46)09Oz;t/q?zCPaJukCuqK6uv{4fpNM5reI}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp38_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

