<?php
if (!defined('IN_HANFOX')) exit('Access Denied');

$pagename = '登录账户';
$pageurl = '?mod=login';
$tplfile = 'login.html';
$table = $DB->table('users');

if (!$smarty->isCached($tplfile)) {
	$smarty->assign('site_title', $pagename.' - '.$options['site_name']);
	$smarty->assign('site_keywords', $options['site_keywords']);
	$smarty->assign('site_description', $options['site_description']);
	$smarty->assign('site_path', get_sitepath().' &raquo; '.$pagename);
    
    if ($_POST['action'] == 'login') {
		$user_email = trim($_POST['email']);
		$user_pass = trim($_POST['pass']);
		
		if (empty($user_email) || !is_valid_email($user_email)) {
			msgbox('请输入有效的电子邮箱！');
		}
        
		if (empty($user_pass)) {
			msgbox('请输入登陆密码！');
		}
		
		$newpass = md5($user_pass);
		$user = $DB->fetch_one("SELECT user_id, user_pass, login_time, login_count FROM $table WHERE user_email='$user_email'");
		if (!$user) {
			msgbox('用户名或密码错误，请重试！');
		} else {
            if ($newpass != $user['user_pass']) {
            	msgbox('用户名或密码错误，请重试！');
            } else {
				//积分
				if (datediff('h', $user['login_time']) == 24) {
					$DB->query("UPDATE $table SET user_score=user_score+1 WHERE user_id=".$user['user_id']." LIMIT 1");
				}
				
				$ip_address = sprintf("%u", ip2long(get_client_ip()));
            	$login_count = $user['login_count'] + 1;
				
				$data = array(
					'login_time' => time(),
					'login_ip' => $ip_address,
					'login_count' => $login_count,
				);
				$where = array('user_id' => $user['user_id']);
				$DB->update($table, $data, $where);
				
				$auth_cookie = authcode("$user[user_id]|$newpass|$login_count");
				$expire = time() + 3600 * 24;
				setcookie('auth_cookie', $auth_cookie, $expire, $options['site_root']);
			
				redirect('?mod=home');
			}
		}
	}
}

smarty_output($tplfile);
?>