<?php
define('IN_ADMIN', TRUE);
define('ROOT_PATH', str_replace('\\', '/', dirname(dirname(__FILE__))).'/');
define('CORE_PATH', ROOT_PATH.'source/');

require(CORE_PATH.'init.php');
require('./function.php');

$pagesize = 30;
$curpage = intval($_GET['page']);
if ($curpage > 1) {
	$start = ($curpage - 1) * $pagesize;
} else {
	$start = 0;
	$curpage = 1;
}

$action = $_GET['act'] ? $_GET['act'] : $_POST['act'];
?>
