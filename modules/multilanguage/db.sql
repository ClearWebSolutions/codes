DROP TABLE IF EXISTS `DBPREFIXvocabulary`;

CREATE TABLE `DBPREFIXvocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang_parent` int(11) DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `phrase` tinyblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;