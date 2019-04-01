<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

function smarty_output($template, $cache_id = NULL, $compile_id = NULL) {
	global $smarty, $options;
	
	template_exists($template);
	
	#common
	$options = stripslashes_deep($options);
	$stats = get_stats();
	$labels = stripslashes_deep(get_labels());
	
	#user login
	$auth_cookie = $_COOKIE['auth_cookie'];
	$user_info = check_user_login($auth_cookie);
	if (empty($user_info)) {
		$login_status = '<div class="top-ulogin"><a href="'.$options['site_root'].'member/?mod=login">登录</a><a href="'.$options['site_root'].'member/?mod=register">注册</a><a href="'.$options['site_root'].'member/?mod=getpwd">找回密码</a></div>';
	} else {
		$login_status = '<div class="top-uinfo">'.$user_info['nick_name'].'，<a href="'.$options['site_root'].'member/?mod=home">我的账户</a> | <a href="'.$options['site_root'].'member/?mod=logout">安全退出</a></div>';
	}

	$smarty->assign('site_root', $options['site_root']);
	$smarty->assign('site_name', $options['site_name']);
	$smarty->assign('site_url', $options['site_url']);
	$smarty->assign('site_copyright', $options['site_copyright']);
	$smarty->assign('search_words', get_format_tags($options['search_words']));
	$smarty->assign('cfg', $options); #options
	$smarty->assign('stat', $stats); #stats
	$smarty->assign('label', $labels); #labels
	$smarty->assign('script_time', get_scripttime()); #script time
	$smarty->assign('login_status', $login_status); #user login
	
	#parse template and output
	$content = $smarty->fetch($template, $cache_id, $compile_id);
	if ($options['is_enabled_rewrite'] == 'yes') {
		$content = rewrite_output($content);
	}
	echo $content;
	
	#gzip
	$buffer = ob_get_contents();
	ob_end_clean();
	$options['is_enabled_gzip'] == 'yes' ? ob_start('ob_gzhandler') : ob_start();
	
	echo $buffer;
}

function msgbox($msg, $url = 'javascript: history.go(-1);') {
	global $smarty;
	
	$template = 'msgbox.html';
	template_exists($template);
	
	$smarty->assign('msg', $msg);
	$smarty->assign('url', $url);
	echo $smarty->fetch('msgbox.html');
	@ob_end_flush();
	exit();
}

function redirect($url) {
    header('location:'.$url, false, 301);
	exit;
}

function get_scripttime() {
	global $DB, $options, $start_time;
	
	$mtime = explode(' ', microtime());
	$end_time = $mtime[1] + $mtime[0];
	$exec_time = number_format(($end_time - $start_time), 6);
	$gzip = $options['is_enabled_gzip'] == 'yes' ? 'Enabled' : 'Disabled';
	
	return 'Processed in '.$exec_time.' second(s), '.$DB->queries.' Queries, Gzip '.$gzip;
}

function insert_script_time() {
	return get_scripttime();
}

/** rss link */
function get_rssfeed($cate_id = 0) {
	global $options;
	
	return '<a href="?mod=rssfeed'.($cate_id > 0 ? '&cid='.$cate_id : '').'" target="_blank"><img src="'.$options['site_root'].'public/images/rss.gif" alt="订阅RssFeed" border="0" /></a>';
}
	
/** site path */
function get_sitepath($cate_id = 0) {
	global $options;
	
	$strpath = '当前位置：<a href="'.$options['site_url'].'">'.$options['site_name'].'</a>'.($cate_id > 0 ? get_category_path($cate_id) : '');
	
	return $strpath;
}

/** format tags */
function get_format_tags($str) {
	$arrstr = !empty($str) && strpos($str, ',') > 0 ? explode(',', $str) : (array) $str;
	$count = count($arrstr);
	
	$newarr = array();
	for ($i = 0; $i < $count; $i++) {
		$tag = trim($arrstr[$i]);
		$newarr[$i]['tag_name'] = $tag;
		$newarr[$i]['tag_link'] = get_search_url('tags', $tag);
	}
	unset($arrstr);
	
	return $newarr;
}
?>