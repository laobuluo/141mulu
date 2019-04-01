<?php
require('common.php');

$pagetitle = SYS_NAME.SYS_VERSION;

$smarty->assign('site_root', $options['site_root']);
$smarty->assign('pagetitle', $pagetitle);
$smarty->display('admin.html');
?>