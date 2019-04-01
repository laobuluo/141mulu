<?php
define('IN_MEMBER', true);

define('ROOT_PATH', str_replace("\\", '/', substr(__FILE__, 0, strrpos(dirname(__FILE__), DIRECTORY_SEPARATOR))).'/');
define('CORE_PATH', ROOT_PATH.'source/');
define('MOD_PATH', ROOT_PATH.'member/module/');

require(CORE_PATH.'init.php');
require(CORE_PATH.'module/rewrite.php');

$module = $_GET['mod'] ? $_GET['mod'] : $_POST['mod'];
if (!isset($module)) $module = 'home';

require(MOD_PATH.'common.php');

/** navitem */
$navitem = array('home' => '会员中心', 'website' => '网站管理', 'addurl' => '网站提交', 'claim' => '网站认领', 'profile' => '个人资料', 'logout' => '安全退出');
$smarty->assign('navitem', $navitem);

$modarr = array('home', 'register', 'login', 'logout', 'activate', 'getpwd', 'reset', 'verify', 'index', 'website', 'claim', 'profile');
$authmod = array('home', 'verify', 'website', 'claim', 'profile');
$verify = array('website', 'claim', 'profile');

if (in_array($module, $modarr)) {
	if (in_array($module, $authmod)) {
		require(CORE_PATH.'module/user.php');
		/** check login  */
		$auth_cookie = $_COOKIE['auth_cookie'];
		$myself = check_user_login($auth_cookie);
		if (empty($myself)) {
			msgbox('您还未登录或无权限！', '?mod=login');
		}
		
		$smarty->assign('myself', $myself);
		
		if (in_array($module, $verify)) {
			if ($myself['user_status'] == 0) {
				$msg = <<<EOT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示信息 - $options[site_name]</title>
<style type="text/css">
body {background: #f5f5f5;}
#msgbox {background: #fff; border: solid 3px #f1f1f1; font: normal 16px/30px normal; margin: 100px auto; padding: 100px 0; text-align: center; width: 500px;}
</style>
</head>

<body>
<div id="msgbox">你还未通过E-mail验证！<br /><a href="?mod=verify">[点击发送验证邮件]</a></div>
</body>
</html>
EOT;
				exit($msg);
			}
		}
	}
	
	$modpath = MOD_PATH.$module.'.php';
	if (is_file($modpath)) {
		require($modpath);
	} else {
		exit('“'.$module.'.php” 模块文件不存在！');
	}
}
?>
