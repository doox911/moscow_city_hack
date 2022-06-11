CREATE TABLE `class_okved` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pk',
  `code` varchar(16) NOT NULL COMMENT 'Код',
  `name` varchar(512) NOT NULL COMMENT 'Наименование',
  `additional_info` text,
  `parent_id` int(11) DEFAULT NULL COMMENT 'Вычестоящий раздел',
  `parent_code` varchar(16) DEFAULT NULL COMMENT 'Код вышестоящего раздела',
  `node_count` smallint(6) NOT NULL DEFAULT '0' COMMENT 'Количество элементов в ветке',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `parent_id` (`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ОК видов экономической деятельности';


ALTER TABLE `class_okved`
  ADD CONSTRAINT `class_okved_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `class_okved` (`id`);
