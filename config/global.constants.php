<?php
defined('INDEX_UNLOCK') or die();
define('SP', '/');
define('DR', DIRECTORY_SEPARATOR);
define('NL', "\n");
define('EMAIL_SYSTEM', $params['email_system']);
define('ADMIN', $params['admin']);
define('EMAIL_ADMIN', $params['email_admin']);
define('COMPACTSITENAME', $params['compact_site_name']);
define('ACCESS_RANGE', 10);
define('GLOBAL_ACCESS_LEVEL', 10);

define('DB_LIBS', $params['db_libs']); 
define('USE_DB', $params['use_db']);

define('DOCUMENT_ROOT', $params['document_root']);
define('ENGINE_PATH', $params['folders']['engine']);
define('CORE_PATH', $params['folders']['engine'].SP.'core');
define('TOOLS_PATH', $params['folders']['application'].SP.'tools');
define('ELEMENTS_PATH', $params['folders']['structure'].SP.'elements');
define('TPL_PATH', $params['folders']['structure'].SP.'templates');

define('DEVEL', $params['devel']);

define('DEBUG', $params['debug']);

define('TBL_USERS', 'users');

define('TBL_USERS_PROFILE', 'users_profile');

define('COMINGSOON', $params['maintenance']);