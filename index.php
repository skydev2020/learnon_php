<?php
// Version
define('VERSION', '1.4.9.5');

// Configuration
require_once('config.php');

// Startup
 require_once(DIR_SYSTEM . 'startup.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/export.php');
require_once(DIR_SYSTEM . 'library/cart.php');
//require_once(DIR_SYSTEM . 'library/weight.php');
//require_once(DIR_SYSTEM . 'library/length.php');

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting");
 
foreach ($query->rows as $setting) {
	$config->set($setting['key'], $setting['value']);
}

// Log 
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);


// Error Handler
function error_handler($errno, $errstr, $errfile, $errline) {
	global $config, $log;

	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . "</b><hr />\n";
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return TRUE;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);
//echo DIR_SYSTEM . 'library/user.php'; exit;
// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Session
$session = new Session();
$registry->set('session', $session);

// Cache
$registry->set('cache', new Cache());

// Document
$registry->set('document', new Document());

// Language
$languages = array();

$query = $db->query("SELECT * FROM " . DB_PREFIX . "language"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	

if (!isset($session->data['language']) || $session->data['language'] != $config->get('config_admin_language')) {
	$session->data['language'] = $config->get('config_admin_language');
}

$registry->set('language', $language);

// Cart
$registry->set('cart', new Cart($registry));

// Currency
$registry->set('currency', new Currency($registry));

// Weight
//$registry->set('weight', new Weight($registry));

// Length
//$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

// Export
$registry->set('export', new ExcelExport($registry));

// Front Controller
$controller = new Front($registry);


$check_admin = "1";
if (isset($request->get['route'])) {
 
	$find_match = "0";
	
	$exclude_controllers = array('account/*', 'user/students/subjects', 'payment_student/*', 'payment/*');
	
	foreach($exclude_controllers as $each_controller) {
		if(strstr($each_controller, '*')) {
			$each_controller = explode('*', $each_controller);
			$each_controller = $each_controller['0'];
			if(preg_match('@^'.$each_controller.'*@', $request->get['route'])) {
				$check_admin = "0";
				break;	
			}
		} else if($each_controller == $request->get['route']) {
			$check_admin = "0";
			break;
		}
	}	
} else {
	$check_admin = "0";
}

// Login
if($check_admin)
$controller->addPreAction(new Action('common/home/login'));

// Permission
if($check_admin)
$controller->addPreAction(new Action('common/home/permission'));
//print_r($session);
//print_r($_SESSION);
// Activity Handler
function log_activity($activity, $activity_details="", $user_id = "", $user_group_id = "") {
	global $db;
	
	if($user_id=="")
	$data['user_id'] = $_SESSION['user_id'];
	else
	$data['user_id'] = $user_id;
	
	if($user_group_id=="")
	$data['user_group_id'] = $_SESSION['group_id'];
	else
	$data['user_group_id'] = $user_group_id;
	
	$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
	$data['platform'] = $_SERVER['HTTP_USER_AGENT'];
	$data['activity'] = $activity;
	$data['activity_details'] = $activity_details;
	
	$db->query("INSERT INTO " . DB_PREFIX . "activity_log SET user_id = '".(int)$db->escape($data['user_id'])."', user_group_id = '".(int)$db->escape($data['user_group_id'])."', ip_address = '".$db->escape($data['ip_address'])."', platform = '".$db->escape($data['platform'])."', activity = '".$db->escape($data['activity'])."', activity_details = '".$db->escape($data['activity_details'])."', date_added = now() ");
	$activity_id = $db->getLastId();
	return $activity_id;
}
// SEO URL's
$controller->addPreAction(new Action('common/seo_url'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/login');
}

// Dispatch
$controller->dispatch($action, new Action('error/not_found'));

// Output
//echo $response->output();
var_dump($response);
$response->output();
?>
