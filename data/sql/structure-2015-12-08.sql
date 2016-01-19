-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 06, 2015 at 07:39 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3-7+squeeze19

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `zs2015_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `session_question`
--

CREATE TABLE IF NOT EXISTS `session_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `session_test_id` int(11) NOT NULL,
  `answer` text COLLATE utf8_czech_ci NOT NULL,
  `note` text COLLATE utf8_czech_ci,
  `score` decimal(10,2) DEFAULT NULL,
  `last_modified_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_session_question_question1_idx` (`question_id`),
  KEY `fk_session_question_session_test1_idx` (`session_test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci AUTO_INCREMENT=41 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `session_question`
--
ALTER TABLE `session_question`
  ADD CONSTRAINT `fk_session_question_question1` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_session_question_session_test1` FOREIGN KEY (`session_test_id`) REFERENCES `session_test` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
