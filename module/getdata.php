<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

require(CORE_PATH.'module/webdata.php');

$type = trim($_GET['type']);
$web_id = intval($_GET['wid']);

if (in_array($type, array('ip', 'grank', 'brank', 'srank', 'arank', 'outstat', 'error'))) {
	$where = "w.web_id=$web_id";
	$web = get_one_website($where);
	if (!$web) {
		exit();
	}
	
	$update_cycle = time() + (3600 * 24 * $options['data_update_cycle']);
	$update_time = time();
	if ($web['web_utime'] < $update_cycle) {
		$DB->query("UPDATE ".$DB->table('webdata')." SET web_utime='$update_time' WHERE web_id=".$web['web_id']);
		#server ip
		if ($type == 'ip') {
			$ip = get_serverip($web['web_url']);
			$ip = sprintf("%u", ip2long($ip));
			$DB->query("UPDATE ".$DB->table('webdata')." SET web_ip='$ip' WHERE web_id=".$web['web_id']);
		}
	
		#google pagerank
		if ($type == 'grank') {
			 $rank = get_pagerank($web['web_url']);
			 $DB->query("UPDATE ".$DB->table('webdata')." SET web_grank='$rank' WHERE web_id=".$web['web_id']);
		}
		
		#baidu pagerank
		if ($type == 'brank') {
			$rank = get_baidurank($web['web_url']);
			$DB->query("UPDATE ".$DB->table('webdata')." SET web_brank='$rank' WHERE web_id=".$web['web_id']);
		}
		
		#sogou pagerank
		if ($type == 'srank') {
			$rank = get_sogourank($web['web_url']);
			$DB->query("UPDATE ".$DB->table('webdata')." SET web_srank='$rank' WHERE web_id=".$web['web_id']);
		}
		
		#alexa rank
		if ($type == 'arank') {
			$rank = get_alexarank($web['web_url']);
			$DB->query("UPDATE ".$DB->table('webdata')." SET web_arank='$rank' WHERE web_id=".$web['web_id']);
		}
	}
	
	#outstat
	if ($type == 'outstat') {
		$DB->query("UPDATE ".$DB->table('webdata')." SET web_outstat=web_outstat+1, web_otime=".time()." WHERE web_id=".$web['web_id']);
	}
	
	#error
	if ($type == 'error') {
		$DB->query("UPDATE ".$DB->table('webdata')." SET web_errors=web_errors+1, web_utime=".time()." WHERE web_id=".$web['web_id']);
	}
}
?>
