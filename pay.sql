-- Adminer 4.6.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `type` mediumtext NOT NULL,
  `content` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config` (`type`, `content`) VALUES
('money',	'0'),
('num',	'0');

DROP TABLE IF EXISTS `content`;
CREATE TABLE `content` (
  `outTradeNo` mediumtext NOT NULL,
  `ispaid` mediumtext NOT NULL,
  `money` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `time` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `order`;
CREATE TABLE `order` (
  `uid` mediumtext NOT NULL,
  `money` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `time` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2021-01-11 09:16:14
