CREATE TABLE `DBPREFIXadmin` (
  `id` int(11) DEFAULT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `meta_description` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `DBPREFIXgalleries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `folder` varchar(255) DEFAULT NULL,
  `sizes` varchar(255) DEFAULT 'admin:127:95:1;',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `DBPREFIXgallery2object` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gallery_id` int(11) DEFAULT NULL,
  `object_id` int(11) DEFAULT NULL,
  `object_table` varchar(255) DEFAULT NULL,
  `ordr` int(11) DEFAULT NULL,
  `multi` tinyint(1) DEFAULT '0',
  `locked` tinyint(1) DEFAULT '0',
  `gallery_title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `DBPREFIXimages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lang_parent` int(10) unsigned DEFAULT NULL,
  `gallery_id` int(10) unsigned DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ordr` int(10) unsigned DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `g2o_id` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `DBPREFIXlanguages` (
  `id` char(2) DEFAULT NULL,
  `language` char(49) DEFAULT NULL,
  `ordr` tinyint(2) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `DBPREFIXlanguages` WRITE;

INSERT INTO `DBPREFIXlanguages` (`id`,`language`,`ordr`,`locked`)
VALUES
	('en','English','1','0');

UNLOCK TABLES;