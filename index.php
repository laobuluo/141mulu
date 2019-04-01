<?php
$mtime = explode(' ', microtime());
$start_time = $mtime[0] + $mtime[1];

define('ROOT_PATH', str_replace('\\', '/', dirname(__FILE__)).'/');
define('CORE_PATH', ROOT_PATH.'source/');
define('MOD_PATH', ROOT_PATH.'module/');

require(CORE_PATH.'init.php');
require(CORE_PATH.'module/link.php');
require(CORE_PATH.'module/adver.php');
require(CORE_PATH.'module/label.php');
require(CORE_PATH.'module/diypage.php');
require(CORE_PATH.'module/category.php');
require(CORE_PATH.'module/website.php');
require(CORE_PATH.'module/user.php');
require(CORE_PATH.'module/stats.php');
require(CORE_PATH.'module/siteurl.php');
require(CORE_PATH.'module/rewrite.php');
require(MOD_PATH.'common.php');

/** module */
$module = $_GET['mod'] ? $_GET['mod'] : $_POST['mod'];
if (!isset($module)) $module = 'index';

$modpath = MOD_PATH.$module.'.php';
if (is_file($modpath)) {
	require($modpath);
		
	#click in
	$refurl = strtolower($_SERVER['HTTP_REFERER']);
	if (preg_match("/^http(s)?:\/\/?([^\/]+)/i", $refurl, $matches)) {
		$domain = $matches[2];
		if (!empty($domain)) {
			$DB->query("UPDATE ".$DB->table('website')." w, ".$DB->table('webdata')." d SET d.web_instat=d.web_instat+1, d.web_itime=".time()." WHERE w.web_id=d.web_id AND web_url='$domain'");
		}				
	}
} else {
	_404();
}
?>