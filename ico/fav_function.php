<?php
function get_url_content($bbb) { 
   $ch = curl_init(); 
   $timeout = 5;  
   curl_setopt ($ch, CURLOPT_URL, $bbb); 
   curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);  
   curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
   curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
   curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
   $file_info['exec']= curl_exec($ch); 
   $file_info['getinfo'] = curl_getinfo($ch); //判断状态 有的情况下无法正确判断ico是否存在
   curl_close($ch); 
   return $file_info; 
}

function isUrl($s)  
{  
    return preg_match('/^http[s]?:\/\/'.  
        '(([0-9]{1,3}\.){3}[0-9]{1,3}'. // IP形式的URL- 199.194.52.184  
        '|'. // 允许IP和DOMAIN（域名）  
        '([0-9a-z_!~*\'()-]+\.)*'. // 三级域验证- www.  
        '([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\.'. // 二级域验证  
        '[a-z]{2,6})'.  // 顶级域验证.com or .museum  
        '(:[0-9]{1,4})?'.  // 端口- :80  
        '((\/\?)|'.  // 如果含有文件对文件部分进行校验  
        '(\/[0-9a-zA-Z_!~\*\'\(\)\.;\?:@&=\+\$,%#-\/]*)?)$/',  
        $s) == 1;  
}
?>