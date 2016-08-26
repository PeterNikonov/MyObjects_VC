SELECT 1;

CREATE TABLE IF NOT EXISTS `_prefix__trash` (
  `object_id` int(11),
  `object_type` varchar(255),
  KEY `type_id` (`object_type`, `object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_prefix__profile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `detail` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='';

CREATE TABLE IF NOT EXISTS `_prefix__division` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `mphone` varchar(256) NOT NULL,
  `hphone` varchar(256) NOT NULL,
  `address` varchar(256) NOT NULL,
  `detail` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `_prefix__service` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `detail` varchar(255),
  `price` int(11),
  `division` int(11),
  `profile` int(11),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;