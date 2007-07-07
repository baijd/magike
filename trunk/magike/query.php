<?php
/**********************************
 * Created on: 2006-12-3
 * File Name : data.php
 * Copyright : Magike Group
 * License   : GNU General Public License 2.0
 *********************************/
 
function query($str)
{
	mysql_query($str) or die(mysql_error());
}

query("SET NAMES 'utf8'");

query("CREATE TABLE `mg_categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `category_name` varchar(100) default NULL,
  `category_postname` varchar(100) default NULL,
  `category_describe` varchar(200) default NULL,
  `category_sort` int(10) unsigned default '0',
  `category_count` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `category_sort` (`category_sort`),
  KEY `category_postname` (`category_postname`(16))
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_categories` (`id`, `category_name`, `category_postname`, `category_describe`, `category_sort`, `category_count`) VALUES 
(1, '默认分类', 'default', '这是一个默认分类', 1, 1)");

query("CREATE TABLE `mg_comment_filters` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `comment_filter_name` varchar(200) default NULL,
  `comment_filter_value` text,
  `comment_filter_type` enum('comment','ping','all') default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("CREATE TABLE `mg_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `comment_user` varchar(64) default NULL,
  `comment_date` int(11) unsigned default NULL,
  `comment_email` varchar(64) default NULL,
  `comment_homepage` varchar(64) default NULL,
  `comment_agent` varchar(200) default NULL,
  `comment_ip` varchar(64) default '0.0.0.0',
  `comment_text` text,
  `comment_title` varchar(64) default NULL,
  `post_id` int(10) unsigned default '0',
  `comment_type` enum('comment','ping') default 'comment',
  `comment_publish` enum('approved','spam','waitting') default 'approved',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_comments` (`id`, `comment_user`, `comment_date`, `comment_email`, `comment_homepage`, `comment_agent`, `comment_ip`, `comment_text`, `comment_title`, `post_id`, `comment_type`, `comment_publish`) VALUES 
(1, 'magike' , 1172842951, 'magike.net@gmail.com', 'http://www.magike.net', 'Magike/1.0', '127.0.0.1', '欢迎您选择Magike', NULL, 1, 'comment', 'approved')");

query("CREATE TABLE `mg_files` (
  `id` int(10) NOT NULL auto_increment,
  `file_name` varchar(200) default NULL,
  `file_guid` varchar(32) default NULL,
  `file_type` varchar(16) default NULL,
  `file_size` varchar(20) default NULL,
  `file_time` int(11) default NULL,
  `file_describe` varchar(200) default NULL,
  PRIMARY KEY  (`id`),
  KEY `file_time` (`file_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("CREATE TABLE `mg_groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(200) default NULL,
  `group_describe` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_groups` (`id`, `group_name`, `group_describe`) VALUES 
(1, '管理员', '这是系统最高权限'),
(2, '访问者', '这是所有的访问者')");

query("CREATE TABLE `mg_link_categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `link_category_name` varchar(100) default NULL,
  `link_category_describe` varchar(100) default NULL,
  `link_category_hide` tinyint(1) default '0',
  `link_category_sort` int(10) default '0',
  `link_category_linksort` enum('asc','desc','rand') default 'asc',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_link_categories` (`id`, `link_category_name`, `link_category_describe`, `link_category_hide`, `link_category_sort`, `link_category_linksort`) VALUES 
(1, 'blogroll', 'blogroll', 0, 1, 'asc')");

query("CREATE TABLE `mg_links` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `link_name` varchar(100) default NULL,
  `link_describe` varchar(200) default NULL,
  `link_url` varchar(100) default NULL,
  `link_image` varchar(100) default NULL,
  `link_category_id` int(11) unsigned default '0',
  PRIMARY KEY  (`id`),
  KEY `link_category_id` (`link_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_links` (`id`, `link_name`, `link_describe`, `link_url`, `link_image`, `link_category_id`) VALUES 
(1, 'Magike', 'Magike官方站点', 'http://www.magike.net', '', 1)");

query("CREATE TABLE `mg_menus` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `menu_name` varchar(200) default NULL,
  `path_id` int(10) unsigned default NULL,
  `menu_parent` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_menus` (`id`, `menu_name`, `path_id`, `menu_parent`) VALUES 
(1, 'lang.admin_menu.all', 3, 0),
(2, 'lang.admin_menu.panel', 4, 1),
(5, 'lang.admin_menu.posts', 5, 0),
(6, 'lang.admin_menu.write', 6, 5),
(7, 'lang.admin_menu.all_posts', 7, 5),
(8, 'lang.admin_menu.all_categories', 8, 5),
(9, 'lang.admin_menu.add_category', 9, 5),
(10, 'lang.admin_menu.comments', 10, 0),
(11, 'lang.admin_menu.all_comments', 11, 10),
(12, 'lang.admin_menu.comment_filters', 12, 10),
(13, 'lang.admin_menu.add_comment_filter', 13, 10),
(14, 'lang.admin_menu.links', 14, 0),
(15, 'lang.admin_menu.all_links', 15, 14),
(16, 'lang.admin_menu.add_link', 16, 14),
(17, 'lang.admin_menu.all_link_categories', 17, 14),
(18, 'lang.admin_menu.add_link_category', 18, 14),
(19, 'lang.admin_menu.users', 19, 0),
(20, 'lang.admin_menu.all_users', 20, 19),
(21, 'lang.admin_menu.add_user', 21, 19),
(22, 'lang.admin_menu.user_group', 22, 19),
(23, 'lang.admin_menu.add_user_group', 23, 19),
(24, 'lang.admin_menu.paths', 24, 0),
(25, 'lang.admin_menu.all_paths', 25, 24),
(26, 'lang.admin_menu.add_path', 26, 24),
(27, 'lang.admin_menu.styles', 27, 0),
(28, 'lang.admin_menu.all_styles', 28, 27),
(29, 'lang.admin_menu.custom_style', 29, 27),
(30, 'lang.admin_menu.settings', 30, 0),
(31, 'lang.admin_menu.setting_public', 31, 30),
(32, 'lang.admin_menu.setting_archives_output', 32, 30),
(33, 'lang.admin_menu.setting_write_archive', 33, 30),
(34, 'lang.admin_menu.setting_comments_output', 34, 30),
(35, 'lang.admin_menu.setting_users', 35, 30),
(36, 'lang.admin_menu.setting_mail', 58, 30)");

query("CREATE TABLE `mg_path_group_mapping` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `path_id` int(10) unsigned default NULL,
  `group_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_path_group_mapping` (`id`, `path_id`, `group_id`) VALUES 
(1, 31, 1),
(2, 24, 1),
(3, 2, 2),
(4, 23, 1),
(5, 22, 1),
(6, 21, 1),
(7, 20, 1),
(8, 19, 1),
(9, 18, 1),
(10, 17, 1),
(11, 16, 1),
(12, 15, 1),
(13, 14, 1),
(14, 13, 1),
(15, 12, 1),
(16, 11, 1),
(17, 10, 1),
(18, 9, 1),
(19, 8, 1),
(20, 1, 2),
(21, 7, 1),
(22, 6, 1),
(23, 5, 1),
(24, 4, 1),
(25, 3, 1),
(26, 1, 1),
(27, 38, 2),
(28, 38, 1),
(29, 44, 1),
(30, 43, 1),
(31, 42, 2),
(32, 42, 1),
(33, 41, 1),
(34, 40, 1),
(35, 28, 1),
(36, 29, 1),
(37, 27, 1),
(38, 30, 1),
(39, 26, 1),
(40, 25, 1),
(41, 32, 1),
(42, 33, 1),
(43, 34, 1),
(44, 35, 1),
(45, 47, 1),
(46, 48, 1),
(47, 49, 1),
(48, 49, 2),
(49, 50, 2),
(50, 50, 1),
(51, 51, 1),
(52, 51, 2),
(53, 52, 1),
(54, 52, 2),
(55, 53, 2),
(56, 53, 1),
(57, 54, 2),
(58, 54, 1),
(59, 36, 1),
(60, 36, 2),
(61, 55, 1),
(62, 55, 2),
(63, 56, 1),
(64, 56, 2),
(65, 57, 1),
(66, 57, 2),
(67, 37, 1),
(68, 37, 2),
(69, 45, 1),
(70, 46, 1),
(71, 58, 1),
(72, 59, 1),
(73, 59, 2),
(74, 60, 1),
(75, 60, 2),
(76, 61, 2)");

query("CREATE TABLE `mg_paths` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `path_name` varchar(64) default NULL,
  `path_action` varchar(20) default NULL,
  `path_file` varchar(64) default NULL,
  `path_cache` int(11) default '0',
  `path_describe` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pt_name` (`path_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_paths` (`id`, `path_name`, `path_action`, `path_file`, `path_cache`,`path_describe`) VALUES 
(1, '/', 'template', '/{\$static_var.template}/index.tpl', 0,'网站主页'),
(2, '/admin/login/', 'template', '/{\$static_var.admin_template}/login.tpl', 0,'后台登陆'),
(3, '/admin/panel/', 'template', '/{\$static_var.admin_template}/index.tpl', 0,'后台主页'),
(4, '/admin/panel/dashboard', 'template', '/{\$static_var.admin_template}/index.tpl', 0,'后台主面板'),
(5, '/admin/posts/', 'template', '/{\$static_var.admin_template}/write.tpl', 0,'后台文章管理'),
(6, '/admin/posts/write/', 'template', '/{\$static_var.admin_template}/write.tpl', 0,'后台撰写文章'),
(7, '/admin/posts/all/', 'template', '/{\$static_var.admin_template}/posts_list.tpl', 0,'后台文章列表'),
(8, '/admin/posts/categories_list/', 'template', '/{\$static_var.admin_template}/categories_list.tpl', 0,'后台文章分类列表'),
(9, '/admin/posts/category/', 'template', '/{\$static_var.admin_template}/add_category.tpl', 0,'后台文章分类编辑'),
(10, '/admin/comments/', 'template', '/{\$static_var.admin_template}/comments.tpl', 0,'后台回响管理'),
(11, '/admin/comments/all/', 'template', '/{\$static_var.admin_template}/comments.tpl', 0,'后台评论列表'),
(12, '/admin/comments/filters_list/', 'template', '/{\$static_var.admin_template}/comment_filters.tpl', 0,'后台回响过滤器列表'),
(13, '/admin/comments/filter/', 'template', '/{\$static_var.admin_template}/add_comment_filter.tpl', 0,'后台编辑回响过滤器'),
(14, '/admin/links/', 'template', '/{\$static_var.admin_template}/links.tpl', 0,'后台链接管理'),
(15, '/admin/links/link_list/', 'template', '/{\$static_var.admin_template}/links.tpl', 0,'后台链接列表'),
(16, '/admin/links/link/', 'template', '/{\$static_var.admin_template}/add_link.tpl', 0,'后台编辑链接'),
(17, '/admin/links/link_categories_list/', 'template', '/{\$static_var.admin_template}/link_categories_list.tpl', 0,'后台链接分类列表'),
(18, '/admin/links/link_category/', 'template', '/{\$static_var.admin_template}/add_link_category.tpl', 0,'后台编辑链接分类'),
(19, '/admin/users/', 'template', '/{\$static_var.admin_template}/users.tpl', 0,'后台用户管理'),
(20, '/admin/users/users_list/', 'template', '/{\$static_var.admin_template}/users.tpl', 0,'后台用户列表'),
(21, '/admin/users/user/', 'template', '/{\$static_var.admin_template}/add_user.tpl', 0,'后台编辑用户'),
(22, '/admin/users/groups_list/', 'template', '/{\$static_var.admin_template}/groups.tpl', 0,'后台用户组列表'),
(23, '/admin/users/group/', 'template', '/{\$static_var.admin_template}/add_group.tpl', 0,'后台编辑用户组'),
(24, '/admin/paths/', 'template', '/{\$static_var.admin_template}/paths.tpl', 0,'后台路径管理'),
(25, '/admin/paths/paths_list/', 'template', '/{\$static_var.admin_template}/paths.tpl', 0,'后台路径列表'),
(26, '/admin/paths/path/', 'template', '/{\$static_var.admin_template}/add_path.tpl', 0,'后台编辑路径'),
(27, '/admin/skins/', 'template', '/{\$static_var.admin_template}/skins.tpl', 0,'后台外观管理'),
(28, '/admin/skins/skins_list/', 'template', '/{\$static_var.admin_template}/skins.tpl', 0,'后台外观列表'),
(29, '/admin/skins/skin/', 'template', '/{\$static_var.admin_template}/add_skin.tpl', 0,'后台自定义外观'),
(30, '/admin/settings/', 'template', '/{\$static_var.admin_template}/setting_public.tpl', 0,'后台设置'),
(31, '/admin/settings/setting_public/', 'template', '/{\$static_var.admin_template}/setting_public.tpl', 0,'后台全局设置'),
(32, '/admin/settings/setting_post/', 'template', '/{\$static_var.admin_template}/setting_post.tpl', 0,'后台设置文章输出'),
(33, '/admin/settings/setting_write/', 'template', '/{\$static_var.admin_template}/setting_write.tpl', 0,'后台设置撰写'),
(34, '/admin/settings/setting_comment/', 'template', '/{\$static_var.admin_template}/setting_comment.tpl', 0,'后台设置回响'),
(35, '/admin/settings/setting_user/', 'template', '/{\$static_var.admin_template}/setting_user.tpl', 0,'后台设置用户'),
(36, '/admin/logout/', 'module_output', 'admin_logout', 0,'后台登出'),
(37, '/archives/[post_id=%d]/trackback/', 'template', '/{\$static_var.xml_template}/trackback.tpl', 0, '引用通告'),
(38, '/helper/validator/', 'json_output', 'validator', 0,'表单验证器'),
(39, '/exception', 'template', '/{\$static_var.template}/exception.tpl', 0,'异常输出'),
(40, '/admin/posts/all/search/', 'template', '/{\$static_var.admin_template}/posts_search_list.tpl', 0,'后台文章搜索'),
(41, '/admin/posts/upload/', 'template', '/{\$static_var.admin_template}/upload.tpl', 0,'后台上传'),
(42, '/res/[file_id=%d]/[file_name=%p]', 'module_output', 'file_output', 0,'文件输出'),
(43, '/admin/skins/get_skin_file/', 'json_output', 'get_skin_file', 0,'后台输出风格元素文件'),
(44, '/admin/posts/files_list/', 'json_output', 'files_list', 0,'后台文件列表'),
(45, '/admin/', 'template', '/{\$static_var.admin_template}/index.tpl', 0,'后台默认主页'),
(46, '/admin/posts/auto_save/', 'template', 'post_input', 0,'自动保存'),
(47, '/admin/posts/write/delete_file/', 'json_output', 'file_input', 0,'后台删除文件'),
(48, '/admin/posts/write/files_list_page_nav/', 'json_output', 'files_list_page_nav', 0,'后台文件列表分页'),
(49, '/archives/[post_id=%d]/', 'template', '/{\$static_var.template}/archive.tpl', 0,'根据ID查看文章'),
(50, '/[post_name=%s]/', 'template', '/{\$static_var.template}/page.tpl', 0,'页面查看'),
(51, '/rss/', 'template', '/{\$static_var.xml_template}/rss_all_posts.tpl', 0,'RSS输出所有文章'),
(52, '/page/[page=%d]/', 'template', '/{\$static_var.template}/index.tpl', 0,'文章分页'),
(53, '/post_comment/[post_id=%d]/', 'template', '/{\$static_var.template}/post_comment.tpl', 0,'提交评论'),
(54, '/category/[category_postname=%a]/', 'template', '/{\$static_var.template}/posts.tpl', 0,'分类文章'),
(55, '/category/[category_postname=%a]/[page=%d]/', 'template', '/{\$static_var.template}/posts.tpl', 0,'分类文章分页'),
(56, '/tags/[tag_name=%s]/', 'template', '/{\$static_var.template}/posts.tpl', 0,'标签文章'),
(57, '/tags/[tag_name=%s]/[page=%d]/', 'template', '/{\$static_var.template}/posts.tpl', 0,'标签文章分页'),
(58, '/admin/settings/setting_mail/', 'template', '/{\$static_var.template}/setting_mail.tpl', 0,'后台设置邮箱'),
(59, '/search/', 'template', '/{\$static_var.template}/posts.tpl', 0,'文章搜索'),
(60, '/rss/archives/[post_id=%d]/', 'template', '/{\$static_var.xml_template}/rss_archives.tpl', 0,'RSS文章输出'),
(61, '/register/', 'template', '/{\$static_var.template}/register.tpl', 0,'用户注册')");

query("CREATE TABLE `mg_post_tag_mapping` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `post_id` int(10) unsigned default NULL,
  `tag_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`),
  KEY `tag_id` (`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("CREATE TABLE `mg_posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `post_title` varchar(200) default NULL,
  `post_name` varchar(64) default NULL,
  `post_time` int(11) unsigned default NULL,
  `post_edit_time` int(11) unsigned default NULL,
  `post_tags` varchar(200) default NULL,
  `post_password` varchar(20) default NULL,
  `post_content` text,
  `category_id` int(10) unsigned default '0',
  `user_id` int(10) unsigned default '0',
  `post_user_name` varchar(64) default NULL,
  `post_comment_num` int(6) unsigned default '0',
  `post_allow_ping` tinyint(1) default '1',
  `post_allow_comment` tinyint(1) default '1',
  `post_allow_feed` tinyint(1) default '1',
  `post_is_draft` tinyint(1) default '0',
  `post_is_hidden` tinyint(1) default '0',
  `post_is_page` tinyint(1) default '0',
  PRIMARY KEY  (`id`),
  KEY `category_id` (`category_id`),
  KEY `post_time` (`post_time`),
  KEY `post_name` (`post_name`(20)),
  KEY `post_tags` (`post_tags`(20))
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_posts` (`id`, `post_title`, `post_name`, `post_time`, `post_edit_time`, `post_tags`, `post_password`, `post_content`, `category_id`, `user_id`, `post_user_name`, `post_comment_num`, `post_allow_ping`, `post_allow_comment`, `post_allow_feed`, `post_is_draft`, `post_is_hidden`, `post_is_page`) VALUES 
(1, '欢迎使用Magike', 'hello_world', 1172842951, 1180181533, '', '', '<p>如果您看到这篇文章,表示您的blog已经安装成功.</p>', 1, 1, 'admin', 1, 1, 1, 1, 0, 0, 0)");

query("CREATE TABLE `mg_statics` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `static_name` varchar(200) default NULL,
  `static_value` text,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `st_name` (`static_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_statics` (`id`, `static_name`, `static_value`) VALUES 
(1, 'template', 'magike'),
(2, 'admin_template', 'admin'),
(3, 'xml_template', 'xml'),
(4, 'siteurl', '{$_POST['siteurl']}'),
(5, 'version', 'Magike 1.0 RC1'),
(6, 'describe', '{$_POST['describe']}'),
(7, 'blog_name', '{$_POST['blogname']}'),
(8, 'language', 'zh_cn_utf8'),
(9, 'charset', 'UTF-8'),
(10, 'content_type', 'text/html'),
(11, 'index', '{$_POST['siteurl']}/index.php'),
(12, 'visitor_group', '2'),
(13, 'comment_date_format', 'M,jS,Y'),
(14, 'time_zone', '28800'),
(15, 'post_sub', '0'),
(16, 'post_page_num', '5'),
(17, 'post_date_format', 'Y年m月d日'),
(18, 'count_posts', '1'),
(19, 'count_comments', '1'),
(20, 'keywords', 'Magike'),
(21, 'post_list_num', '10'),
(22, 'comment_list_num', '10'),
(23, 'comment_email', '0'),
(24, 'write_editor_rows', '16'),
(25, 'write_default_name', 'nickname'),
(26, 'write_default_category', '1'),
(27, 'write_auto_save', '0'),
(28, 'comment_check', '0'),
(29, 'user_allow_register', '0'),
(30, 'user_register_group', '2'),
(31, 'comment_email_notnull', '1'),
(32, 'comment_homepage_notnull', '0'),
(33, 'comment_ajax_validator', '1'),
(34, 'referer_denny', '0'),
(35, 'smtp_host', ''),
(36, 'smtp_port', '25'),
(37, 'smtp_user', ''),
(38, 'smtp_pass', ''),
(39, 'smtp_auth', '0'),
(40, 'smtp_ssl', '0')");

query("CREATE TABLE `mg_tags` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tag_name` varchar(32) default NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `tag_name` (`tag_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("CREATE TABLE `mg_user_group_mapping` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_id` int(10) unsigned default NULL,
  `group_id` int(10) unsigned default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_user_group_mapping` (`id`, `user_id`, `group_id`) VALUES 
(1, 1, 1)");

query("CREATE TABLE `mg_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user_name` varchar(64) default NULL,
  `user_realname` varchar(64) default NULL,
  `user_password` varchar(64) default NULL,
  `user_mail` varchar(64) default NULL,
  `user_url` varchar(64) default NULL,
  `user_nick` varchar(64) default NULL,
  `user_about` text,
  `user_register` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

query("INSERT INTO `mg_users` (`id`, `user_name`, `user_realname`, `user_password`, `user_mail`, `user_url`, `user_nick`, `user_about`, `user_register`) VALUES 
(1, 'admin', 'magike', '827ccb0eea8a706c4c34a16891f84e7b', '{$_POST['email']}', '{$_POST['siteurl']}', 'magike_nick', NULL, '".date("Y-m-d H:i:s")."')");
?>
