-- MySQL Script generated by MySQL Workbench
-- 11/22/15 12:27:14
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema zs2015_3_test
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema zs2015_3_test
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zs2015_3_test` DEFAULT CHARACTER SET utf8 ;
USE `zs2015_3_test` ;

-- -----------------------------------------------------
-- Table `zs2015_3_test`.`position`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`position` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`technology`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`technology` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`seniority`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`seniority` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`applicant`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`applicant` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `position_id` INT NOT NULL,
  `technology_id` INT NOT NULL,
  `seniority_id` INT NOT NULL,
  `first_name` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `last_name` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `birth_year` CHAR(4) CHARACTER SET 'utf8' NOT NULL,
  `note` TEXT CHARACTER SET 'utf8' NULL,
  `photo` BLOB NULL,
  `created_at` DATETIME NOT NULL,
  `last_modified_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_applicant_position1_idx` (`position_id` ASC),
  INDEX `fk_applicant_technology1_idx` (`technology_id` ASC),
  INDEX `fk_applicant_seniority1_idx` (`seniority_id` ASC),
  CONSTRAINT `fk_applicant_position1`
    FOREIGN KEY (`position_id`)
    REFERENCES `zs2015_3_test`.`position` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_applicant_technology1`
    FOREIGN KEY (`technology_id`)
    REFERENCES `zs2015_3_test`.`technology` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_applicant_seniority1`
    FOREIGN KEY (`seniority_id`)
    REFERENCES `zs2015_3_test`.`seniority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) CHARACTER SET 'utf8' NOT NULL,
  `password` CHAR(40) NOT NULL,
  `ip` CHAR(15) CHARACTER SET 'utf8' NULL,
  `last_login` DATETIME NULL DEFAULT NULL,
  `lognum` INT NOT NULL DEFAULT 0,
  `browser` VARCHAR(255) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`file`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`file` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `applicant_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `filename` VARCHAR(255) NOT NULL,
  `file` BLOB NOT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_file_applicant1_idx` (`applicant_id` ASC),
  CONSTRAINT `fk_file_applicant1`
    FOREIGN KEY (`applicant_id`)
    REFERENCES `zs2015_3_test`.`applicant` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`test`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`test` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `position_id` INT NOT NULL,
  `technology_id` INT NOT NULL,
  `seniority_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  `last_modified_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_test_position1_idx` (`position_id` ASC),
  INDEX `fk_test_technology1_idx` (`technology_id` ASC),
  INDEX `fk_test_seniority1_idx` (`seniority_id` ASC),
  CONSTRAINT `fk_test_position1`
    FOREIGN KEY (`position_id`)
    REFERENCES `zs2015_3_test`.`position` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_technology1`
    FOREIGN KEY (`technology_id`)
    REFERENCES `zs2015_3_test`.`technology` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_seniority1`
    FOREIGN KEY (`seniority_id`)
    REFERENCES `zs2015_3_test`.`seniority` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `test_id` INT NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `type` ENUM('Text', 'Choice') NOT NULL,
  `text` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `last_modified_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_question_test1_idx` (`test_id` ASC),
  CONSTRAINT `fk_question_test1`
    FOREIGN KEY (`test_id`)
    REFERENCES `zs2015_3_test`.`test` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`session_test`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`session_test` (
  `id` INT NOT NULL,
  `applicant_id` INT NOT NULL,
  `test_id` INT NOT NULL,
  `hash` CHAR(40) NOT NULL,
  `status` ENUM('Assigned', 'Submitted', 'Started', 'Reviewed') NOT NULL,
  `score` INT NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `last_modified_at` DATETIME NOT NULL,
  `deleted_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_test_session_applicant1_idx` (`applicant_id` ASC),
  INDEX `fk_test_session_test1_idx` (`test_id` ASC),
  CONSTRAINT `fk_test_session_applicant1`
    FOREIGN KEY (`applicant_id`)
    REFERENCES `zs2015_3_test`.`applicant` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_test_session_test1`
    FOREIGN KEY (`test_id`)
    REFERENCES `zs2015_3_test`.`test` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `text` TEXT NOT NULL,
  `is_correct` TINYINT(1) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_answer_question1_idx` (`question_id` ASC),
  CONSTRAINT `fk_answer_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `zs2015_3_test`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`session_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`session_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `session_test_id` INT NOT NULL,
  `note` TEXT NULL,
  `last_modified_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_session_question_question1_idx` (`question_id` ASC),
  INDEX `fk_session_question_session_test1_idx` (`session_test_id` ASC),
  CONSTRAINT `fk_session_question_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `zs2015_3_test`.`question` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_session_question_session_test1`
    FOREIGN KEY (`session_test_id`)
    REFERENCES `zs2015_3_test`.`session_test` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zs2015_3_test`.`session_answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zs2015_3_test`.`session_answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `session_question_id` INT NOT NULL,
  `answer_id` INT NOT NULL,
  `text` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_session_answer_answer1_idx` (`answer_id` ASC),
  INDEX `fk_session_answer_session_question1_idx` (`session_question_id` ASC),
  CONSTRAINT `fk_session_answer_answer1`
    FOREIGN KEY (`answer_id`)
    REFERENCES `zs2015_3_test`.`answer` (`id`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_session_answer_session_question1`
    FOREIGN KEY (`session_question_id`)
    REFERENCES `zs2015_3_test`.`session_question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `zs2015_3_test`.`position`
-- -----------------------------------------------------
START TRANSACTION;
USE `zs2015_3_test`;
INSERT INTO `zs2015_3_test`.`position` (`id`, `name`) VALUES (1, 'Developer');
INSERT INTO `zs2015_3_test`.`position` (`id`, `name`) VALUES (2, 'Tester');
INSERT INTO `zs2015_3_test`.`position` (`id`, `name`) VALUES (3, 'Manager');
INSERT INTO `zs2015_3_test`.`position` (`id`, `name`) VALUES (4, 'Assistant');

COMMIT;


-- -----------------------------------------------------
-- Data for table `zs2015_3_test`.`technology`
-- -----------------------------------------------------
START TRANSACTION;
USE `zs2015_3_test`;
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (1, '.NET');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (2, 'JAVA');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (3, 'Javascript');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (4, 'iOS');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (5, 'Android');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (6, 'C++');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (7, 'PHP');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (8, 'Drupal');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (9, 'CAD');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (10, 'UI/UX');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (11, 'QA');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (12, 'PM');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (13, 'Marketing');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (14, 'Sales');
INSERT INTO `zs2015_3_test`.`technology` (`id`, `name`) VALUES (15, 'Administration');

COMMIT;


-- -----------------------------------------------------
-- Data for table `zs2015_3_test`.`seniority`
-- -----------------------------------------------------
START TRANSACTION;
USE `zs2015_3_test`;
INSERT INTO `zs2015_3_test`.`seniority` (`id`, `name`) VALUES (1, 'Junior');
INSERT INTO `zs2015_3_test`.`seniority` (`id`, `name`) VALUES (2, 'Middle');
INSERT INTO `zs2015_3_test`.`seniority` (`id`, `name`) VALUES (3, 'Senior');

COMMIT;
