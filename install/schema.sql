CREATE TABLE IF NOT EXISTS `[table_prefix]ads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `size` varchar(255) NOT NULL,
  `code` text NOT NULL,
  `views` mediumint(6) NOT NULL,
  `clicks` mediumint(6) NOT NULL,
  PRIMARY KEY (`id`)
) [charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]cats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `prefix` varchar(255) NOT NULL,
  `sort` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
)[charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]files` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `file_group` tinyint(1) NOT NULL DEFAULT '1',
  `file_real_name` varchar(255) NOT NULL,
  `file_clean_name` varchar(255) NOT NULL,
  `file_physical_name` varchar(255) NOT NULL,
  `file_size` int(11) NOT NULL,
  `file_mime_type` varchar(100) NOT NULL,
  `file_extension` varchar(100) NOT NULL,
  `file_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) [charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]langs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pkg` varchar(255) NOT NULL DEFAULT 'ROOT',
  `var_key` varchar(100) NOT NULL,
  `en` mediumtext NOT NULL,
  `ar` mediumtext NOT NULL,
  `fr` mediumtext NOT NULL,
  `de` mediumtext NOT NULL,
  `es` mediumtext NOT NULL,
  `it` mediumtext NOT NULL,
  `cz` mediumtext NOT NULL,
  `gr` mediumtext NOT NULL,
  `eo` mediumtext NOT NULL,
  `ir` mediumtext NOT NULL,
  `in` mediumtext NOT NULL,
  `hr` mediumtext NOT NULL,
  `is` mediumtext NOT NULL,
  `ja` mediumtext NOT NULL,
  `kk` mediumtext NOT NULL,
  `ko` mediumtext NOT NULL,
  `la` mediumtext NOT NULL,
  `ro` mediumtext NOT NULL,
  `ru` mediumtext NOT NULL,
  `tr` mediumtext NOT NULL,
  `zh` mediumtext NOT NULL,
  `version` int(9) NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `var_key` (`var_key`)
) [charset_collate];


CREATE TABLE IF NOT EXISTS `[table_prefix]messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `sender_data` mediumtext NOT NULL,
  `content` mediumtext NOT NULL,
  `unread` tinyint(1) NOT NULL DEFAULT '1',
  `msgtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) [charset_collate];


CREATE TABLE IF NOT EXISTS `[table_prefix]options` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `option_name` (`name`)
) [charset_collate];


CREATE TABLE IF NOT EXISTS `[table_prefix]pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '2',
  `prefix` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `options` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) [charset_collate] ;


CREATE TABLE IF NOT EXISTS `[table_prefix]postattrs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `attr_key` varchar(255) DEFAULT NULL,
  `attr_value` longtext,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`)
) [charset_collate];


CREATE TABLE IF NOT EXISTS `[table_prefix]posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hash` varchar(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `provider` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `file_id` bigint(20) NOT NULL DEFAULT '0',
  `thumb_id` bigint(20) NOT NULL DEFAULT '0',
  `cat_id` bigint(20) NOT NULL,
  `source_id` bigint(20) NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `description` mediumtext NOT NULL,
  `content` longtext,
  `views` bigint(20) NOT NULL DEFAULT '0',
  `votes` bigint(20) NOT NULL DEFAULT '0',
  `lastview` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comments` bigint(20) NOT NULL DEFAULT '0',
  `comments_update` bigint(20) NOT NULL DEFAULT '0',
  `createdon` int(20) NOT NULL,
  `createdby_id` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `publishedon` int(20) NOT NULL,
  `publishedby_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) [charset_collate];


CREATE TABLE IF NOT EXISTS `[table_prefix]sources` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `terms` mediumtext NOT NULL,
  `options` mediumtext,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) [charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]userattrs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `attr_key` varchar(255) DEFAULT NULL,
  `attr_value` longtext,
  PRIMARY KEY (`id`)
)[charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `manager` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` varchar(3) NOT NULL,
  `token` text NOT NULL,
  `recover_hash` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
)[charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]votes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `user_ip` int(11) NOT NULL DEFAULT '0',
  `vote` tinyint(1) NOT NULL DEFAULT '0',
  `vote_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`user_id`)
) [charset_collate];

CREATE TABLE IF NOT EXISTS `[table_prefix]wall_posts` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `location` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lng` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `createdon` int(11) NOT NULL,
  `createdby` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) [charset_collate];