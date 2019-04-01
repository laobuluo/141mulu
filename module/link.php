<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

$pagename = '友情链接';
$pageurl = '?mod=link';
$tempfile = 'link.html';
$table = $DB->table('links');

$pagesize = 10;
$curpage = intval($_GET['page']);
if ($curpage > 1) {
	$start = ($curpage - 1) * $pagesize;
} else {
	$start = 0;
	$curpage = 1;
}

if (!$smarty->isCached($tempfile)) {
	$smarty->assign('pagename', $pagename);
	$smarty->assign('site_title', $pagename.' - '.$options['site_title']);
	$smarty->assign('site_keywords', $options['site_keywords']);
	$smarty->assign('site_description', $options['site_description']);
	$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);
	$smarty->assign('site_rss', get_rssfeed());
	
	$linklist = get_link_list('link_display = 1', 'link_id', 'DESC', $start, $pagesize);
	$total = $DB->get_count($table, $where);
	$showpage = showpage($pageurl, $total, $curpage, $pagesize);
	
	$smarty->assign('total', $total);
	$smarty->assign('linklist', $linklist);
	$smarty->assign('showpage', $showpage);
	unset($linklist);
}

smarty_output($tempfile);
?>