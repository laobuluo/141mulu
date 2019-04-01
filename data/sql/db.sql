SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;




CREATE TABLE IF NOT EXISTS `dir_advers` (
  `adver_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `adver_type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `adver_name` varchar(50) NOT NULL DEFAULT '',
  `adver_url` varchar(255) NOT NULL,
  `adver_code` text NOT NULL,
  `adver_etips` varchar(50) NOT NULL DEFAULT '',
  `adver_days` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `adver_date` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`adver_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;



INSERT INTO `dir_advers` (`adver_id`, `adver_type`, `adver_name`, `adver_url`, `adver_code`, `adver_etips`, `adver_days`, `adver_date`) VALUES
(1, 2, '首页头部（960*75）', '', '广告位一', '', 0, 1353709986),
(2, 2, '内容页 250*250', '', '内容页 250*250', '', 0, 1353743360),
(3, 2, '广告位三', '', '广告位三', '', 0, 1353710383),
(4, 2, '首页右侧250', '首页右侧250', '', '', 0, 1411285411);



CREATE TABLE IF NOT EXISTS `dir_categories` (
  `cate_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `root_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cate_name` varchar(50) NOT NULL DEFAULT '',
  `cate_dir` varchar(50) NOT NULL DEFAULT '',
  `cate_url` varchar(255) NOT NULL,
  `cate_isbest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `cate_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `cate_keywords` varchar(100) NOT NULL DEFAULT '',
  `cate_description` varchar(255) NOT NULL DEFAULT '',
  `cate_arrparentid` varchar(255) NOT NULL,
  `cate_arrchildid` text NOT NULL,
  `cate_childcount` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `cate_postcount` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cate_id`),
  KEY `root_id` (`root_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `dir_feedback` (
  `fb_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `fb_nick` varchar(50) NOT NULL,
  `fb_email` varchar(50) NOT NULL DEFAULT '',
  `fb_content` text NOT NULL,
  `fb_date` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`fb_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `dir_labels` (
  `label_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `label_name` varchar(50) NOT NULL DEFAULT '',
  `label_intro` varchar(255) NOT NULL DEFAULT '',
  `label_content` text NOT NULL,
  PRIMARY KEY (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `dir_links` (
  `link_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `link_name` varchar(50) NOT NULL DEFAULT '',
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_logo` varchar(255) NOT NULL DEFAULT '',
  `link_display` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `link_order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `dir_options` (
  `option_name` varchar(30) NOT NULL DEFAULT '',
  `option_value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



INSERT INTO `dir_options` (`option_name`, `option_value`) VALUES
('site_name', 'LplyDir'),
('site_title', '141网站目录'),
('site_url', 'https://www.itbulu.com'),
('site_root', '/'),
('admin_email', 'lply.com@qq.com'),
('site_keywords', '分类目录,网站收录,网站提交,网站目录,网站推广,网站登录'),
('site_description', '全人工编辑的开放式网站分类目录，收录国内外、各行业优秀网站，旨在为用户提供网站分类目录检索、优秀网站参考、网站推广服务。'),
('site_copyright', 'Copyright ? 2008-2012 www.lply.net All Rights Reserved'),
('regname_small', '2'),
('regname_large', '6'),
('regname_forbid', 'admin\r\n管理员\r\n来派领域\r\nlply'),
('home_instat', '20'),
('home_outstat', '20'),
('home_isbest', '50'),
('home_new', '13'),
('is_enabled_gzip', 'yes'),
('is_enabled_submit', 'yes'),
('submit_close_reason', ''),
('data_update_cycle', '1'),
('is_enabled_register', 'yes'),
('register_email_verify', 'no'),
('is_enabled_rewrite', 'yes'),
('rewrite_suffix', '.html'),
('smtp_host', 'smtp.163.com'),
('smtp_port', '25'),
('smtp_auth', 'yes'),
('smtp_user', 'username@163.com'),
('smtp_pass', 'password'),
('filter_words', 'sb\r\n发票');



CREATE TABLE IF NOT EXISTS `dir_pages` (
  `page_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL DEFAULT '',
  `page_intro` varchar(255) NOT NULL DEFAULT '',
  `page_content` text NOT NULL,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `dir_users` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_type` enum('admin','member','recruit','vip') NOT NULL DEFAULT 'member',
  `user_email` varchar(50) NOT NULL,
  `user_pass` char(32) NOT NULL,
  `open_id` char(32) NOT NULL,
  `nick_name` varchar(20) NOT NULL,
  `user_qq` varchar(20) NOT NULL,
  `user_score` smallint(5) unsigned NOT NULL DEFAULT '0',
  `verify_code` varchar(32) NOT NULL,
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `join_time` int(10) unsigned NOT NULL DEFAULT '0',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `login_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `login_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `dir_webdata` (
  `web_id` int(10) unsigned NOT NULL DEFAULT '0',
  `web_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `web_brank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `web_grank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `web_srank` tinyint(2) unsigned NOT NULL DEFAULT '0',
  `web_arank` int(10) unsigned NOT NULL DEFAULT '0',
  `web_instat` int(10) unsigned NOT NULL DEFAULT '0',
  `web_outstat` int(10) unsigned NOT NULL DEFAULT '0',
  `web_views` int(10) unsigned NOT NULL DEFAULT '0',
  `web_errors` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `web_itime` int(10) unsigned NOT NULL DEFAULT '0',
  `web_otime` int(10) unsigned NOT NULL DEFAULT '0',
  `web_utime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`web_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `dir_website` (
  `web_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cate_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `web_name` varchar(100) NOT NULL DEFAULT '',
  `web_url` varchar(255) NOT NULL DEFAULT '',
  `web_qq` varchar(20) NOT NULL,
  `web_tags` varchar(100) NOT NULL,
  `web_intro` text NOT NULL,
  `web_istop` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `web_isbest` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `web_status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `web_ctime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`web_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
