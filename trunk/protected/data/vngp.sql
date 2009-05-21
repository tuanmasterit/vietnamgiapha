/*
SQLyog Community Edition- MySQL GUI v8.03 
MySQL - 5.0.41-community-nt : Database - giapha
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

/*Table structure for table `family` */

DROP TABLE IF EXISTS `family`;

CREATE TABLE `family` (
  `id` int(11) NOT NULL auto_increment,
  `phahe_id` int(11) NOT NULL,
  `father_id` int(11) NOT NULL,
  `level` smallint(4) default NULL,
  `sort_order` smallint(4) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `family` */

/*Table structure for table `person` */

DROP TABLE IF EXISTS `person`;

CREATE TABLE `person` (
  `id` int(11) NOT NULL auto_increment,
  `phahe_id` int(11) NOT NULL,
  `family_id` int(11) NOT NULL,
  `pid` varchar(16) collate utf8_unicode_ci default NULL,
  `pic` varchar(64) collate utf8_unicode_ci NOT NULL,
  `sts` tinyint(1) NOT NULL default '2',
  `gender` char(1) collate utf8_unicode_ci default 'm',
  `name_thuy` varchar(96) collate utf8_unicode_ci default NULL,
  `name_huy` varchar(96) collate utf8_unicode_ci default NULL,
  `name_tu` varchar(32) collate utf8_unicode_ci default NULL,
  `name_thuong` varchar(32) collate utf8_unicode_ci default NULL,
  `conthumay` tinyint(4) default NULL,
  `dob` varchar(32) collate utf8_unicode_ci default NULL,
  `dod` varchar(32) collate utf8_unicode_ci default NULL,
  `wod` varchar(255) collate utf8_unicode_ci default NULL,
  `huong_tho` tinyint(2) default NULL,
  `detail` text collate utf8_unicode_ci,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `person` */

/*Table structure for table `phahe` */

DROP TABLE IF EXISTS `phahe`;

CREATE TABLE `phahe` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(150) collate utf8_unicode_ci NOT NULL,
  `content` text collate utf8_unicode_ci,
  `created_on` datetime default NULL,
  `updated_on` datetime default NULL,
  `created_by_id` int(11) default NULL,
  `created_name` varchar(150) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `phahe` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
