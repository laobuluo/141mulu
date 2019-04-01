<?php 
header('Content-type: image/x-icon');
include_once("fav_function.php");
error_reporting(0); 

$url = @$_GET['url'];

if($url){
$url = preg_replace('/http\:\/\//i','',$url);	
$url = 'http://'.$url;	

//非法域名时调用默认文件
if(isUrl($url)!='1'){
   $file = "i/no.png";
   $file = @file_get_contents($file);
   echo $file;	
   exit;
}

$arr = parse_url($url);
$domain = $arr['host'];



$dir = 'cache/fav';
$fav = $dir."/".$domain.".ico";
	
//调用缓存文件
$file = @file_get_contents($fav);
if($file){
	echo $file;exit;
}



	
//新建文件
$curl = get_url_content($http.$domain."/favicon.ico");
$file = $curl['exec'];
$zt = $curl['getinfo'];

if($file && $zt['http_code']=='200' && $zt['content_type']=='image/x-icon' && $domain!='wx.qq.com'){
   $f2 = $file;
   echo $f2;
}else{
   $curl = get_url_content($url);
   $file = $curl['exec'];
		@preg_match('|href=\"(.*?)\.ico\"|i',$file,$a);
		
        if($a[1]){
           $a[1] .='.ico';
		   
		   $curl = get_url_content($a[1]);	
	       $file = $curl['exec'];
	       $zt = $curl['getinfo'];	
		   if($zt['http_code']=='200'){//类似昵图网的独立图片存储链接情况处理
			  $f2 = $file;
		      echo $f2;  			   
		   }else{		   
		      $u = $http.$domain.'/'.$a[1];
			  $curl = get_url_content($u);
		     $file = $curl['exec'];
	         $zt = $curl['getinfo'];
		      if($file && $zt['http_code']=='200'){
			     $f2 = $file;
		         echo $f2;
		      }else{
				 $file = "i/no.png";			  
                 $f2 = @file_get_contents($file);
                 echo $f2;			     
		     }
		   }
		}else{
		    $file = "i/no.png";			  
            $f2 = @file_get_contents($file);
            echo $f2;
		}
	}
    if($f2)
       $filesize = @file_put_contents($fav,$f2);
}else{
	header("Content-Type:text/html;charset=utf-8");
	echo 'erro';
}
?> 