<?php
/** 获取META信息 */
function get_sitemeta($url) {
	$url = format_url($url);
	$data = get_url_content($url);
		
	$meta = array();
	if (!empty($data)) {
		#Title
		preg_match('/<TITLE>([\w\W]*?)<\/TITLE>/si', $data, $matches);
		if (!empty($matches[1])) {
			$meta['title'] = $matches[1];
		}
		
		#Keywords
		preg_match('/<META\s+name="keywords"\s+content="([\w\W]*?)"/si', $data, $matches);		
		if (empty($matches[1])) {
			preg_match("/<META\s+name='keywords'\s+content='([\w\W]*?)'/si", $data, $matches);			
		}
		if (empty($matches[1])) {
			preg_match('/<META\s+content="([\w\W]*?)"\s+name="keywords"/si', $data, $matches);			
		}
		if (empty($matches[1])) {
			preg_match('/<META\s+http-equiv="keywords"\s+content="([\w\W]*?)"/si', $data, $matches);			
		}
		if (!empty($matches[1])) {
			$meta['keywords'] = $matches[1];
		}
		
		#Description
		preg_match('/<META\s+name="description"\s+content="([\w\W]*?)"/si', $data, $matches);		
		if (empty($matches[1])) {
			preg_match("/<META\s+name='description'\s+content='([\w\W]*?)'/si", $data, $matches);			
		}
		if (empty($matches[1])) {
			preg_match('/<META\s+content="([\w\W]*?)"\s+name="description"/si', $data, $matches);					
		}
		if (empty($matches[1])) {
			preg_match('/<META\s+http-equiv="description"\s+content="([\w\W]*?)"/si', $data, $matches);			
		}
		if (!empty($matches[1])) {
			$meta['description'] = $matches[1];
		}
	}

	return $meta; 
}

/** Server IP */
function get_serverip($url) {
	$domain = get_domain($url);
	if ($domain) {
		$ip = gethostbyname($domain);
	} else {
		$ip = 0;
	}
	
	return $ip;
}

/** Google Pagerank */
function get_pagerank($url) {
	require(CORE_PATH.'include/pagerank.php');
	
	$pr = new PageRank();
	$rank = $pr->getGPR($url);
	return $rank;
}

/** Baidu Pagerank */
function get_baidurank($url) {
	 $data= file_get_contents('http://mytool.chinaz.com/baidusort.aspx?host='.$url);
        preg_match_all("/<div class=\"siteinfo\">(.*)<font color=\"blue\">(.*)<\/font>(.*)<font color=\"blue\">(.*)<\/font>(.*)<font color=\"blue\">(.*)<\/font>(.*)<font color=\"blue\">(.*)<\/font>(.*)<\/div>/siU",$data,$arr);
         // print_r($arr);
         if(isset($arr[2][0])){
           return  $arr[2][0];  //返回权重值
         }else{
           return  "0";
         }
}

/** Sogou Pagerank */
function get_sogourank($url) {
	$data = get_url_content("http://rank.ie.sogou.com/sogourank.php?ur=http://$url");
	if (preg_match('/sogourank=(\d+)/i', $data, $matches)) {
		$rank = intval($matches[1]);
	} else {
		$rank = 0;
	}
	return $rank;
}

/** Alexa Rank */
function get_alexarank($url) {
	$data = get_url_content("http://xml.alexa.com/data?cli=10&dat=nsa&ver=quirk-searchstatus&url=$url");
	if (preg_match('/<POPULARITY[^>]*URL[^>]*TEXT[^>]*\"([0-9]+)\"/i', $data, $matches)) {
		$rank = strip_tags($matches[1]);
	} else {
		$rank = 0;
	}
	return $rank;
}
?>
