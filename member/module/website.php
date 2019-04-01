<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

require(CORE_PATH.'module/category.php');
require(CORE_PATH.'module/website.php');
require(CORE_PATH.'module/siteurl.php');

$pageurl = '?mod=website';
$tplfile = 'website.html';
$table = $DB->table('website');

$action = isset($_GET['act']) ? $_GET['act'] : 'list';
$smarty->assign('action', $action); 

if (!$smarty->isCached($tplfile)) {
	/** list */
	if ($action == 'list') {
		$pagename = '网站管理';
		$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
		$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);
		
		$pagesize = 10;
		$curpage = intval($_GET['page']);
		if ($curpage > 1) {
			$start = ($curpage - 1) * $pagesize;
		} else {
			$start = 0;
			$curpage = 1;
		}
		
		$where = "w.user_id=".$myself['user_id'];
	
		$weblist = get_website_list($where, 'ctime', 'DESC', $start, $pagesize);
		$total = $DB->get_count($table.' w', $where);
		$showpage = showpage($pageurl, $total, $curpage, $pagesize);
		
		$smarty->assign('pagename', $pagename);
		$smarty->assign('weblist', $weblist);
		$smarty->assign('total', $total);
		$smarty->assign('showpage', $showpage);
	}
	
	/** add */
	if ($action == 'add') {
		$pagename = '网站提交';
		
		#统计当日可提交的站点数量
		if ($options['submit_limit'] > 0) {
			$time = time() - (3600 * 12);
			$today_count = $DB->get_count($DB->table('websites'), "web_ctime>='$time'");
			$submit_limit = $options['submit_limit'] - $today_count;
			$smarty->assign('submit_limit', $submit_limit);
		}
		
		$smarty->assign('pagename', $pagename);
		$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
		$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);	
		$smarty->assign('category_option', get_category_option(0, 0, 0));	
		$smarty->assign('do', 'saveadd');
	}
	
	/** edit */
	if ($action == 'edit') {
		$pagename = '网站编辑';
		$web_id = intval($_GET['wid']);
		$where = "a.user_id='$myself[user_id]' AND a.web_id='$web_id'";
		$web = get_one_website($where);
		if (!$web) {
			msgbox('您要修改的内容不存在或无权限！');
		}

		#分类ID
		$parent_cids = get_category_parent_ids($web['cate_id']).','.$web['cate_id'];
		if (strpos($parent_cids, ',') !== false) {
			$cate_pids = explode(',', $parent_cids);
			array_shift($cate_pids);
		} else {
			$cate_pids = (array) $parent_cids;
		}

		$web['web_ip'] = long2ip($web['web_ip']);
		
		$smarty->assign('pagename', $pagename);
		$smarty->assign('site_title', $pagename.' - '.$options['site_title']);
		$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);	
		$smarty->assign('cate_pids', $cate_pids);
		$smarty->assign('category_option', get_category_option(0, $web['cate_id'], 0));
		$smarty->assign('web', $web);	
		$smarty->assign('do', 'saveedit');
	}
	
	/** save */
	if (in_array($_POST['do'], array('saveadd', 'saveedit'))) {
		$cate_id = intval($_POST['cate_id']);
		$web_name = trim($_POST['web_name']);
		$web_url = trim($_POST['web_url']);
		$web_tags = trim($_POST['web_tags']);
		$web_intro = trim($_POST['web_intro']);
		$web_ip = trim($_POST['web_ip']);
		$web_grank = intval($_POST['web_grank']);
		$web_brank = intval($_POST['web_brank']);
		$web_srank = intval($_POST['web_srank']);
		$web_arank = intval($_POST['web_arank']);
		$web_time = time();
		
		if ($cate_id <= 0) {
			msgbox('请选择网站所属分类！');
		} else {
			$cate = get_one_category($cate_id);
			if ($cate['cate_childcount'] > 0) {
				msgbox('指定的分类下有子分类，请选择子分类进行操作！');
			}
		}
	
		if (empty($web_name)) {
			msgbox('请输入网站名称！');
		} else {
			if (!censor_words($options['filter_words'], $web_name)) {
				msgbox('网站名称中含有非法关键词！');	
			}
		}
		
		if (empty($web_url)) {
			msgbox('请输入网站域名！');
		} else {
			if (!is_valid_domain($web_url)) {
				msgbox('请输入正确的网站域名！');
			}
		}
		
		if (!empty($web_tags)) {
			if (!censor_words($options['filter_words'], $web_tags)) {
				msgbox('TAG标签中含有非法关键词！');
			}
			
			$web_tags = str_replace('，', ',', $web_tags);
			$web_tags = str_replace(',,', ',', $web_tags);
			if (substr($web_tags, -1) == ',') {
				$web_tags = substr($web_tags, 0, strlen($web_tags) - 1);
			}
		}
			
		if (empty($web_intro)) {
			msgbox('请输入网站简介！');
		} else {
			if (!censor_words($options['filter_words'], $web_intro)) {
				msgbox('网站简介中含有非法关键词！');	
			}
		}
		
		$web_ip = sprintf("%u", ip2long($web_ip));
		
		$web_data = array(
			'cate_id' => $cate_id,
			'user_id' => $myself['user_id'],
			'web_name' => $web_name,
			'web_url' => $web_url,
			'web_tags' => $web_tags,
			'web_intro' => $web_intro,
			'web_status' => 2,
			'web_ctime' => $web_time,
		);
		
		if ($_POST['do'] == 'saveadd') {
    		$query = $DB->query("SELECT web_id FROM $table WHERE web_url='$web_url'");
    		if ($DB->num_rows($query)) {
        		msgbox('您所提交的网站已存在！');
    		}
			$DB->insert($table, $web_data);
			$insert_id = $DB->insert_id();
			
			$stat_data = array(
				'web_id' => $insert_id,
				'web_ip' => $web_ip,
				'web_grank' => $web_grank,
				'web_brank' => $web_brank,
				'web_srank' => $web_srank,
				'web_arank' => $web_arank,
				'web_utime' => $web_time,
			);
			$DB->insert($DB->table('webdata'), $stat_data);
		
			msgbox('网站提交成功！', $pageurl);	
		} elseif ($_POST['do'] == 'saveedit') {
			$web_id = intval($_POST['web_id']);
			$where = array('web_id' => $web_id);
			$DB->update($table, $web_data, $where);
			
			$stat_data = array(
				'web_ip' => $web_ip,
				'web_grank' => $web_grank,
				'web_brank' => $web_brank,
				'web_srank' => $web_srank,
				'web_arank' => $web_arank,
				'web_utime' => $web_time,
			);
			$DB->update($DB->table('webdata'), $stat_data, $where);
			
			msgbox('网站编辑成功！', $pageurl);
		}
	}
}

smarty_output($tplfile);
?>
