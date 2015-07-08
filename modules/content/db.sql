DROP TABLE IF EXISTS `DBPREFIXpages`;

CREATE TABLE `DBPREFIXpages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT '0',
  `lang_parent` int(11) DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL,
  `ordr` int(11) DEFAULT '0',
  `locked` tinyint(1) DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` tinyblob,
  `meta_keywords` tinyblob,
  `content_areas` tinyint(1) DEFAULT NULL,
  `content1` blob,
  `content2` blob,
  `content3` blob,
  `content4` blob,
  `content5` blob,
  `content1_title` varchar(255) DEFAULT NULL,
  `content2_title` varchar(255) DEFAULT NULL,
  `content3_title` varchar(255) DEFAULT NULL,
  `content4_title` varchar(255) DEFAULT NULL,
  `content5_title` varchar(255) DEFAULT NULL,
  `child_page_template_id` int(10) DEFAULT '0',
  `new` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;