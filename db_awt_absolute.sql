-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2014 at 03:10 AM
-- Server version: 5.5.34
-- PHP Version: 5.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_awt_qanda`
--

-- --------------------------------------------------------

--
-- Table structure for table `answerrep`
--

CREATE TABLE IF NOT EXISTS `answerrep` (
  `repId` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionTitle` varchar(150) NOT NULL,
  `userId` int(11) NOT NULL,
  PRIMARY KEY (`repId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `answerreport`
--

CREATE TABLE IF NOT EXISTS `answerreport` (
  `reportaId` bigint(20) NOT NULL AUTO_INCREMENT,
  `answerId` bigint(20) NOT NULL,
  `answer` text NOT NULL,
  PRIMARY KEY (`reportaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE IF NOT EXISTS `answers` (
  `answerId` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionTitle` varchar(150) DEFAULT NULL,
  `userId` int(11) DEFAULT NULL,
  `answer` text,
  `status` tinyint(4) DEFAULT NULL,
  `postDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `votes` bigint(20) DEFAULT NULL,
  `acceptedAns` tinyint(4) NOT NULL,
  `seenNoti` tinyint(4) NOT NULL,
  PRIMARY KEY (`answerId`),
  KEY `questionTitle` (`questionTitle`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`answerId`, `questionTitle`, `userId`, `answer`, `status`, `postDate`, `votes`, `acceptedAns`, `seenNoti`) VALUES
(11, 'how to select from mysql databse', 1, 'Select * from yourtable;', 1, '2014-01-06 18:30:00', 0, 0, 0),
(12, 'how to add a class?', 1, 'This is how you wite a clas in php \n\nclass className extends CI_Model {\n\n}', 1, '2014-01-07 18:30:00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `answervotes`
--

CREATE TABLE IF NOT EXISTS `answervotes` (
  `voteId` bigint(20) NOT NULL AUTO_INCREMENT,
  `answerId` bigint(20) NOT NULL,
  `userId` int(11) NOT NULL,
  `voteType` tinyint(4) NOT NULL,
  PRIMARY KEY (`voteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_bin NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('5fa5924518184b4cb49fd73e1a631289', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36', 1389125164, 'a:4:{s:9:"user_data";s:0:"";s:7:"user_id";s:1:"1";s:8:"username";s:7:"hh_shan";s:6:"status";s:1:"1";}'),
('88bfce8f946e5b48bf20efc67a046a83', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0', 1389119954, 'a:4:{s:9:"user_data";s:0:"";s:7:"user_id";s:1:"1";s:8:"username";s:7:"hh_shan";s:6:"status";s:1:"1";}'),
('d3a6c79711407989d066086460797f8d', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0', 1389126953, 'a:3:{s:9:"user_data";s:0:"";s:22:"flash:new:captcha_word";s:8:"ZpWrawJo";s:22:"flash:new:captcha_time";d:1389126954.1451508998870849609375;}'),
('e8b7c92ecca1574674e2f13ac1eb8d65', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0', 1389125429, 'a:4:{s:9:"user_data";s:0:"";s:7:"user_id";s:1:"2";s:8:"username";s:7:"dhlshan";s:6:"status";s:1:"1";}');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_bin NOT NULL,
  `login` varchar(50) COLLATE utf8_bin NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `questionreport`
--

CREATE TABLE IF NOT EXISTS `questionreport` (
  `reportqId` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionTitle` varchar(150) NOT NULL,
  PRIMARY KEY (`reportqId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `questionreport`
--

INSERT INTO `questionreport` (`reportqId`, `questionTitle`) VALUES
(3, 'delete all data from database');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `userId` int(11) NOT NULL,
  `questionTitle` varchar(150) NOT NULL,
  `questionBody` text NOT NULL,
  `tags` varchar(500) NOT NULL,
  `postDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL,
  `votes` bigint(20) NOT NULL,
  `seenNoti` tinyint(4) NOT NULL,
  `answered` tinyint(4) NOT NULL,
  PRIMARY KEY (`questionTitle`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`userId`, `questionTitle`, `questionBody`, `tags`, `postDate`, `status`, `votes`, `seenNoti`, `answered`) VALUES
(3, 'delete all data from database', 'I was stuck in this for some time. please help me delete data from a  database using php', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0),
(1, 'help needed in adding an image via codeigniter', 'i need to add an image to the database using codeigniter php', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0),
(3, 'help needed in c   to create a class', 'i need to create a class in c  . i am new to this', 'c++', '2014-01-07 17:52:47', 1, 1, 0, 0),
(1, 'how to add a class?', 'i need to learn how to write a class in php', 'php', '2014-01-07 18:43:51', 1, 0, 0, 0),
(3, 'how to create a databse in mysql with php', 'i need help in creating a database in my sql', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0),
(3, 'how to select from mysql databse', 'i need help in trying to do a mysql select in php', 'php', '2014-01-07 19:58:48', 1, 1, 0, 0),
(1, 'i need help with updating a server', 'i need to  update a server using php', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0),
(1, 'insert data to table help', 'i need help with inserting data to a mysql table', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0),
(3, 'my select query givesn an error', 'My select query gives a fatal error', 'c++', '2014-01-06 18:30:00', 1, 0, 0, 0),
(3, 'perform an array loop', 'i need help in looping through an array in php', 'php', '2014-01-06 18:30:00', 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `questionvotes`
--

CREATE TABLE IF NOT EXISTS `questionvotes` (
  `voteId` bigint(20) NOT NULL AUTO_INCREMENT,
  `questionTitle` varchar(150) NOT NULL,
  `userId` int(11) NOT NULL,
  `voteType` tinyint(4) NOT NULL,
  PRIMARY KEY (`voteId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `questionvotes`
--

INSERT INTO `questionvotes` (`voteId`, `questionTitle`, `userId`, `voteType`) VALUES
(60, 'help needed in c   to create a class', 3, 1),
(61, 'how to select from mysql databse', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tagName` varchar(100) NOT NULL,
  `tagDesc` text NOT NULL,
  PRIMARY KEY (`tagName`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tagName`, `tagDesc`) VALUES
('c++', 'Related desktop/ firmware programming'),
('css', 'cascading style sheets'),
('js', 'javascript'),
('php', 'Web programming and hosting');

-- --------------------------------------------------------

--
-- Table structure for table `userreport`
--

CREATE TABLE IF NOT EXISTS `userreport` (
  `reportuId` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `userRep` bigint(20) NOT NULL,
  PRIMARY KEY (`reportuId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userType` varchar(1) COLLATE utf8_bin NOT NULL,
  `userRep` bigint(20) NOT NULL,
  `userLP` bigint(20) NOT NULL,
  `isReported` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `last_login`, `created`, `modified`, `userType`, `userRep`, `userLP`, `isReported`) VALUES
(1, 'hh_shan', '$2a$08$aY89JGMWrz9rb.B9xP3Q0e1NyeeEnUopqqysdM2mRhoI/OXvwYEDe', 'hh_shan@live.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2014-01-08 01:36:46', '2013-12-24 18:20:57', '2014-01-07 20:06:46', 'T', 106, 108, 0),
(2, 'dhlshan', '$2a$08$scUGUTdRMaayRL4GXuDB9ei7aZ.Xxf5ypY/goQHsKym46V/5.aZDS', 'dhl.hasitha@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2014-01-08 02:05:45', '2013-12-24 18:38:33', '2014-01-07 20:35:45', 'A', 17, 0, 0),
(3, 'hashani', '$2a$08$UmCTRjS4mEpwVSZYEB5e4.lcnNRkIilO4UMFhteH3S.lvwtNafRBq', 'hh.odnanref@gmail.com', 1, 0, NULL, NULL, NULL, NULL, NULL, '::1', '2014-01-08 01:28:45', '2014-01-05 06:28:21', '2014-01-07 19:58:45', 'T', 0, 26, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_bin NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_bin NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `country` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `website` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=13 ;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `country`, `website`) VALUES
(1, 1, NULL, NULL),
(2, 2, NULL, NULL),
(3, 9, NULL, NULL),
(4, 10, NULL, NULL),
(5, 11, NULL, NULL),
(6, 12, NULL, NULL),
(7, 13, NULL, NULL),
(8, 3, NULL, NULL),
(9, 4, NULL, NULL),
(10, 5, NULL, NULL),
(11, 6, NULL, NULL),
(12, 7, NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
