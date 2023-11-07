<?php
define('__ROOT__', rtrim(str_replace('\\', '/',dirname(__FILE__)), '/').'/');
define('__BASEURI__', dirname($_SERVER['PHP_SELF']) == '/' ? '' : dirname($_SERVER['PHP_SELF']));
define('__HOME__', dirname(__FILE__).'/');
define('__LOG_DIR__', dirname(__FILE__).'/logs/');
define('__LOCK_DIR__', dirname(__FILE__).'/logs/locks/');
define('DB_HOST', 'localhost');
define('DB_USER', 'temp');
define('DB_PASS', 'temp');
define('DB_NAME', 'bitcoin');
define('TRANSPORT_API_SECRET', '');
define('TRANSPORT_API_ENC_METHOD', 'AES-256-CBC');
define('C_LIGHTING_PATH', '/usr/local/bin/lightning-cli');
define('C_LIGHTING_LIMIT', 3);

define('REMOTE_IP', 'XX.XX.XX.XX');
define('REMOTE_PORT', 22);
define('REMOTE_USER', 'user');
define('REMOTE_PASS', 'passwd');

define('ADMIN_PWD_SHA1', 'sha1_hash');

define('API_BASE_URL', 'https://example.com/btc-api/api.php');

define('REMOTE_API_PUBLIC_KEY_FILE_PATH', dirname(__FILE__).'/public.pem');

define('PUBLIC_KEY_FILE_PATH', dirname(__FILE__).'/public.pem');
define('PRIVATE_KEY_FILE_PATH', dirname(__FILE__).'/private.pem');
define('CACHE_FILE_PATH', dirname(__FILE__).'/cache/cache.db');
define('SETTINGS_FILE_PATH', dirname(__FILE__).'/cache/settings.db');
define('CACHE_IV_FILE_PATH', dirname(__FILE__).'/cache/cacheiv.db');

define('PRIV_KEY_PASS', 'pass');

?>