<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */


	
define('DB_NAME', 'galaxyex_db');
define('DB_USER', 'galaxyex_user');
define('DB_PASSWORD', 'xxxxxxxxxx');

define('DB_HOST', '127.0.0.1');

define('GE_ADMIN_EMAIL', 'xxx@xxx.com');
define('GE_ADMIN_NAME', 'xxx xxx');
define('SEND_ERROR_NOTIFICATION', true);
define('ERROR_NOTIFICATION_EMAIL', 'xxx@xxx.com');
define('ERROR_NOTIFICATION_NAME', 'xxx xxx');

define('FAQ_PAGE_ID', 34);
define('GUIDE_PAGE_ID', 118);
	

@ini_set('memory_limit', '256M');


$_SERVER['REQUEST_URI'] = str_replace('http://galaxy-explorer.localdev', '', $_SERVER['REQUEST_URI']);

define('ERROR_NOTIFICATION_SUBJECT', 'GALAXY EXPLORER - An error occurred on '.$_SERVER['SERVER_NAME']);
define('NOTIFICATION_SUBJECT', 'GALAXY EXPLORER - Notification on '.$_SERVER['SERVER_NAME']);
define('UPDATE_SUBJECT', 'GALAXY EXPLORER - Hourly Update on '.$_SERVER['SERVER_NAME']);

//check date for competition
$date = new DateTime("now", new DateTimeZone('Australia/Sydney') );
$date_comp_finish = new DateTime( '4 Sep 2015 23:59:59 AEST');



if($date < $date_comp_finish){
	define('COMPETITION_ACTIVE', true);
}else{
	define('COMPETITION_ACTIVE', false);
}


define('COMPETITION_IMAGES_GROUP', '10');
define('COMPETITION_IMAGES_SINGLE', '10');
define('PAGE_ID_SCIENCE', '20');
define('EMAILS_FROM', 'xxx@xxx.com');
define('EMAILS_FROM_NAME', 'ABC Galaxy Explorer');

define('GE_CAPTCHA_PUBLIC_KEY', 'xxxxxxxxxx');
define('GE_CAPTCHA_PRIVATE_KEY', 'xxxxxxxxxx');


define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

if (!session_id())
	session_start();

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '6!E:AHxF_FVf-<@bxC)V-$ea|ZDKg#(Q.zLCJS~|*4]-yO]o^vb3-58b}Tz1Q9+n');
define('SECURE_AUTH_KEY',  'kOdRCFD<->T]ffa{[b!uL 5:>3F* gwA`6*Ni7~%gF>s$(IYox>s8.#$mH**j47Y');
define('LOGGED_IN_KEY',    '8i-0=ae>[c.:.jE[Ww2fk-P3;j|yJY8O<wpX|N$2G+:;=aLfcin{WX69%BL$4jRl');
define('NONCE_KEY',        'W//gdH<Xe=a9u94-}%e/< u1@=?TP1vp~8];;2J|}w_|ZQC:Q,F4a[`h17W}h-=t');
define('AUTH_SALT',        '&As=[Jm@P.h2a,JcS+7~r0]=ueN-{#2CSGSKFlm?d,=k&%..v)K^_6T<Vp kZT/:');
define('SECURE_AUTH_SALT', ' D5thZNpuVY;W)x>i~UcjK1|cW5;ZW}a`&A+{w@kIhu?zl+o,)PVX;.5veXHjx|p');
define('LOGGED_IN_SALT',   'kf1ugy#Z=c|~bUx[GuhT}<<E?k34j8jVwT-GkqrAIG~P`;!=@HD2|fc0H%i)vzV8');
define('NONCE_SALT',       'x7H?uG-l]*C5AtSb:(b;mE(T)8Cb-+(}L&<R#1 <pS2]A]H!-_wTmJzZ3UDDW#H%');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/*----------------------------------------------------------------------------------------------
 Disable all core updates - WP-CONFIG.PHP
------------------------------------------------------------------------------------------------*/
/** Disable all core updates */
define( 'WP_AUTO_UPDATE_CORE', false );

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

add_filter('xmlrpc_enabled', '__return_false'); // disable xml-rpc calls

