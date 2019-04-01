<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

$pagename = '会员中心';
$pageurl = '?mod=home';
$tplfile = 'home.html';

if (!$smarty->isCached($tplfile)) {
	$smarty->assign('pagename', $pagename);
	$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
	$smarty->assign('site_path', get_sitepath());
}

smarty_output($tplfile);
?>