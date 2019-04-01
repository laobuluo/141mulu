<?php
/** category */
function get_category_url($cate_id = 0, $page = 1) {
	global $options;
	
	if ($cate_id > 0) {
		$cate = get_one_category($cate_id);
		$cate_dir = !empty($cate['cate_dir']) ? $cate['cate_dir'] : 'category';
		unset($cate);
		$page = isset($page) && $page > 0 ? $page : 1;
			
		if ($options['is_enabled_rewrite'] == 'yes') {
			$strurl = $options['site_root'].'webdir/'.$cate_dir.'/'.$cate_id.'-'.$page.$options['rewrite_suffix'];
		} else {
			$strurl = '?mod=webdir&cid='.$cate_id;
		}		
	}

	return $strurl;
}

/** update */
function get_update_url($days, $page = 1) {
	global $options;
	
	$days = isset($days) && $days > 0 ? $days : 0;
	$page = isset($page) && $page > 0 ? $page : 1;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		$strurl = $options['site_root'].'update/'.$days.'-'.$page.$options['rewrite_suffix'];
	} else {
		$strurl = '?mod=update&days='.$days;
	}
	
	return $strurl;
}

/** archives */
function get_archives_url($date, $page = 1) {
	global $options;
	
	$date = isset($date) && strlen($date) == 6 ? $date : 0;
	$page = isset($page) && $page > 0 ? $page : 1;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		$strurl = $options['site_root'].'archives/'.$date.'-'.$page.$options['rewrite_suffix'];
	} else {
		$strurl = '?mod=archives&date='.$date;
	}
	
	return $strurl;
}

/** search */
function get_search_url($type = 'name', $query, $page = 1) {
	global $options;

	$query = isset($query) && !empty($query) ? urlencode($query) : '';
	$page = isset($page) && $page > 0 ? $page : 1;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		$strurl = $options['site_root'].'search/'.$type.'/'.$query.'-'.$page.$options['rewrite_suffix'];
	} else {
		$strurl = '?mod=search&type='.$type.'&query='.$query;
	}
	
	return $strurl;
}

/** website */
function get_website_url($web_id, $abs_path = false) {
	global $options;
	
	if ($abs_path) {
		$url_prefix = $options['site_url'];
	} else {
		$url_prefix = $options['site_root'];
	}
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		$strurl = $url_prefix.'siteinfo/'.$web_id.$options['rewrite_suffix'];
	} else {
		$strurl = $url_prefix.'?mod=siteinfo&wid='.$web_id;
	}
	
	return $strurl;
}

/** diypage */
function get_diypage_url($page_id) {
	global $options;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		$strurl = $options['site_root'].'diypage/'.$page_id.$options['rewrite_suffix'];
	} else {
		$strurl = '?mod=diypage&pid='.$page_id;
	}
	
	return $strurl;
}

/** rssfeed */
function get_rssfeed_url($cate_id) {
	global $options;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		if ($cate_id > 0) {
			$strurl = $options['site_root'].'rssfeed/'.$cate_id.$options['rewrite_suffix'];
		} else {
			$strurl = $options['site_root'].'rssfeed/';
		}
	} else {
		if ($cate_id > 0) {
			$strurl = '?mod=rssfeed&cid='.$cate_id;
		} else {
			$strurl = '?mod=rssfeed';
		}		
	}
	
	return $strurl;
}

/** sitemap */
function get_sitemap_url($cate_id) {
	global $options;
	
	if ($options['is_enabled_rewrite'] == 'yes') {
		if ($cate_id > 0) {
			$strurl = $options['site_root'].'sitemap/'.$cate_id.$options['rewrite_suffix'];
		} else {
			$strurl = $options['site_root'].'sitemap/';
		}
	} else {
		if ($cate_id > 0) {
			$strurl = '?mod=sitemap&cid='.$cate_id;
		} else {
			$strurl = '?mod=sitemap';
		}		
	}
	
	return $strurl;
}

/** thumbs */
function get_webthumb($web_url) {
	return 'http://www.5118.com/static/'.$web_url.'/'.$web_url.'_s.jpg';
}
?>