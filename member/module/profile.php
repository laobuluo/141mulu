<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

$pagename = '个人资料';
$pageurl = '?mod=profile';
$tplfile = 'profile.html';
$table = $DB->table('users');

if (!$smarty->isCached($tplfile)) {
	$smarty->assign('pagename', $pagename);
	$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
	$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);
	
	if ($_POST['do'] == 'save') {
		$old_pass = trim($_POST['old_pass']);
		$new_pass = trim($_POST['new_pass']);
		$new_pass1 = trim($_POST['new_pass1']);
		$nick_name = trim($_POST['nick_name']);
		$user_qq = trim($_POST['user_qq']);
		
		if (empty($old_pass)) {
			msgbox('请输入原始密码！');
		} else {
			$user = $DB->fetch_one("SELECT user_pass FROM $table WHERE user_id=".$myself['user_id']);
			if ($user['user_pass'] != md5($old_pass)) {
				unset($user);
				msgbox('您输入的原始密码不正确！');
			}
		}
		
		if (empty($new_pass)) {
			msgbox('请输入新密码！');
		}
		
		if (empty($new_pass1)) {
			msgbox('请输入确认密码！');
		}
		
		if ($new_pass != $new_pass1) {
			msgbox('两次密码输入不一致，请重新输入！');
		}
		if (strlen_utf8($nick_name) < $options['regname_small'] || strlen_utf8($nick_name) > $options['regname_large']) {
			msgbox('昵称长度请保持在'.$options['regname_small'].'-'.$options['regname_large'].'个字符！每个汉字算一个字符~');
		}

		if($options['regname_forbid']){
			$detail=explode("\r\n",$options['regname_forbid']);
			if(in_array($nick_name,$detail)){
				msgbox('受保护的帐号,不允许使用,请更换一个吧！');
			}
		}

		if (empty($user_qq)) {
			msgbox('请输入正确的腾讯QQ帐号！');
		} else {
			if (strlen($user_qq) < 5 || strlen($user_qq) > 11) {
				msgbox('QQ长度请保持在5-11个字符！');
 			}
		}
		
		$data = array(
			'user_pass' => md5($new_pass),
			'nick_name' => $nick_name,
			'user_qq' => $user_qq,
		);
		
		$where = array(
			'user_id' => $myself['user_id'],
		);
		
		$DB->update($table, $data, $where);
		msgbox('个人资料修改成功！', $pageurl);
	}
}

smarty_output($tplfile);
?>