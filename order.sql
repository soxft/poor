-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `uid` mediumtext NOT NULL,
  `money` mediumtext NOT NULL,
  `order_id` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `time` mediumtext NOT NULL,
  `ip` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2020-11-29 12:38:19
