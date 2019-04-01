<?php
error_reporting(E_ALL ^ E_NOTICE);

define('IN_HANFOX', true);

/** 设置默认编码 */
header('Content-type: text/html; charset=utf-8');

/** 设置时区 */
if (function_exists('date_default_timezone_set')) {
	date_default_timezone_set('PRC');
}

/** PHP错误日志 */
ini_set('error_log', ROOT_PATH.'data/errlog/debug.log');
ini_set('log_errors', '1');

/** SESSION */
@session_cache_limiter('private, must-revalidate');
@session_start();

/** 是否安装 */
if (!is_file(ROOT_PATH.'data/install.lock')) {
	header("Location: ./install/index.php\n");
	exit;
}

// 防止一些低级的XSS
if($_SERVER['REQUEST_URI']) {
	$temp = urldecode($_SERVER['REQUEST_URI']);
	if(strpos($temp, '<') !== false || strpos($temp, '>') !== false || strpos($temp, '(') !== false || strpos($temp, '"') !== false) {
		exit('Request Bad url');
	}
}

// 防止 PHP 5.1.x 使用时间函数报错
if(PHP_VERSION > '5.1') {
	@date_default_timezone_set('UTC');
}

/** 加载配置文件 */
if (is_file(ROOT_PATH.'config.php')) {
	require(ROOT_PATH.'config.php');
} else {
	exit('config.php file is missing!');
}

require(CORE_PATH.'include/mysql.php');
require(CORE_PATH.'include/cache.php');
require(CORE_PATH.'include/function.php');
require(CORE_PATH.'include/validate.php');
require(CORE_PATH.'version.php');

/** 对传入的变量进行转义 */
if (phpversion() < '5.3.0') {
	set_magic_quotes_runtime(0);
	@ini_set('magic_quotes_sybase', 0);
}

lply_magic_quotes();

/** 初始化数据库信息 */
$DB = new MySQL(DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME, DB_CHARSET, TABLE_PREFIX, DB_PCONNECT);

/** 加载系统设置 */
require(CORE_PATH.'module/option.php');

$options = get_options();
$options = array_change_key_case($options, CASE_LOWER);
if (substr($options['site_root'], -1) != '/') {
	$options['site_root'] .= '/';
}

/** 初始化变量 */
$php_self = htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']);
$base_name = basename($php_self);
$site_root = substr($php_self, 0, -strlen($base_name));
$site_url = htmlspecialchars('http://'.$_SERVER['HTTP_HOST'].substr($php_self, 0, strrpos($php_self, '/')).'/');

$timescope = array('0' => '所有时间内', '1' => '24小时内', '3' => '三天内', '7' => '一周内', '30' => '一月内', '365' => '一年内');
$user_types = array('member' => '注册会员', 'recruit' => '快速收录', 'vip' => 'VIP会员');

define('SITE_ROOT', $site_root);
define('SITE_URL', $site_url);

/** 加载模板引擎 */
require(CORE_PATH.'include/smarty.php');
?>