
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `permissions` varchar(10) NOT NULL DEFAULT '',
  `homedir` varchar(1000) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `akey` varchar(255) NOT NULL DEFAULT '',
  `usage` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `akey` (`akey`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

DELETE FROM `users`;
INSERT INTO `users` (`id`, `username`, `password`, `permissions`, `homedir`, `email`, `akey`, `usage`) VALUES
	(1, 'admin', '0192023a7bbd73250516f069df18b500', 'rwu', '', '', '', NULL),
	(2, 'guest', '', 'r', '', '', '', NULL);
	