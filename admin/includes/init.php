<?php 

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'Users'.DS.'bauerpm'.DS.'Desktop'.DS.'php_projects'.DS.'Gallery');

defined('INCLUDES_PATH') ? null : define('INCLUDES_PATH', SITE_ROOT.DS.'admin'.DS.'includes');

require_once('functions.php');
require_once('new_config.php');
require_once('database.php');
require_once('Db_object.php');
require_once('user.php');
require_once('photo.php');
require_once('session.php');
?>
