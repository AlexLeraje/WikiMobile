--
--
--
DROP TABLE IF EXISTS `wm_ads`;
CREATE TABLE IF NOT EXISTS `wm_ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL,
  `name` text NOT NULL,
  `link` text NOT NULL,
  `time` int(11) NOT NULL,
  `style` text NOT NULL,
  `view` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_discusion`;
CREATE TABLE IF NOT EXISTS `wm_discusion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `page` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_edit` int(11) NOT NULL,
  `user_edit` varchar(25) NOT NULL,
  `lastedit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_files`;
CREATE TABLE IF NOT EXISTS `wm_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `filename` text NOT NULL,
  `page` int(11) NOT NULL,
  `att` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `view` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_guests`;
CREATE TABLE IF NOT EXISTS `wm_guests` (
  `session` varchar(32) NOT NULL,
  `ip` varchar(17) NOT NULL,
  `user_agent` tinytext NOT NULL,
  `lastvisit` int(11) NOT NULL,
  PRIMARY KEY (`session`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_history`;
CREATE TABLE IF NOT EXISTS `wm_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page` int(11) NOT NULL,
  `file` int(11) NOT NULL,
  `numb` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `lang` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_invites`;
CREATE TABLE IF NOT EXISTS `wm_invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_mail_ban`;
CREATE TABLE IF NOT EXISTS `wm_mail_ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `userid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_mod`;
CREATE TABLE IF NOT EXISTS `wm_mod` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(5) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `time` int(11) NOT NULL,
  `name` text NOT NULL,
  `comments` tinyint(2) NOT NULL,
  `path` text NOT NULL,
  `att` int(11) NOT NULL,
  `lang` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_pages`;
CREATE TABLE IF NOT EXISTS `wm_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` text NOT NULL,
  `dir` text NOT NULL,
  `time` int(11) NOT NULL,
  `id_create` int(11) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `last_edit` int(11) NOT NULL,
  `comments` tinyint(2) NOT NULL,
  `can_edit` varchar(50) NOT NULL,
  `id_edit` int(11) NOT NULL,
  `user_edit` varchar(25) NOT NULL,
  `comm_time` int(11) NOT NULL,
  `lang_edit` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_page_comm`;
CREATE TABLE IF NOT EXISTS `wm_page_comm` (
  `page` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`page`,`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_page_lang`;
CREATE TABLE IF NOT EXISTS `wm_page_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `lang` varchar(3) NOT NULL,
  `dir` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_page_view`;
CREATE TABLE IF NOT EXISTS `wm_page_view` (
  `page` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`page`,`userid`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_settings`;
CREATE TABLE IF NOT EXISTS `wm_settings` (
  `key` tinytext NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--
DROP TABLE IF EXISTS `wm_smiles`;
CREATE TABLE IF NOT EXISTS `wm_smiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `refid` int(11) NOT NULL,
  `type` varchar(2) NOT NULL,
  `pattern` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_users`;
CREATE TABLE IF NOT EXISTS `wm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `rights` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `lastvisit` int(11) NOT NULL,
  `ip` varchar(17) NOT NULL,
  `ua` text NOT NULL,
  `lang` varchar(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_users_ban`;
CREATE TABLE IF NOT EXISTS `wm_users_ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `to_time` int(11) NOT NULL,
  `id_who` int(11) NOT NULL,
  `md_name` varchar(25) NOT NULL,
  `reason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ;
--
--
--
DROP TABLE IF EXISTS `wm_users_inactive`;
CREATE TABLE IF NOT EXISTS `wm_users_inactive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `rights` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
--
--
--
DROP TABLE IF EXISTS `wm_users_info`;
CREATE TABLE IF NOT EXISTS `wm_users_info` (
  `userid` int(11) NOT NULL,
  `sex` tinyint(2) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `icq` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `born` varchar(50) NOT NULL,
  `site` text NOT NULL,
  `about` text NOT NULL,
  `place` text NOT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--
--
--