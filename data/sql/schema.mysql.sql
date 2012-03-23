SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

-- CREATE SCHEMA IF NOT EXISTS `aperophp` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;

-- -----------------------------------------------------
-- Table `City`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `City` ;

CREATE  TABLE IF NOT EXISTS `City` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nom_UNIQUE` (`name` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User` ;

CREATE  TABLE IF NOT EXISTS `User` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `lastname` VARCHAR(80) NULL ,
  `firstname` VARCHAR(80) NULL ,
  `email` VARCHAR(80) NOT NULL UNIQUE,
  `token` VARCHAR(64) NULL ,
  `member_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_User_Member` (`member_id` ASC) ,
  CONSTRAINT `fk_User_Member`
    FOREIGN KEY (`member_id` )
    REFERENCES `Member` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Member`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Member` ;

CREATE  TABLE IF NOT EXISTS `Member` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `username` VARCHAR(80) NOT NULL UNIQUE,
  `password` VARCHAR(80) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `User_City`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User_City` ;

CREATE  TABLE IF NOT EXISTS `User_City` (
  `user_id` INT NOT NULL ,
  `city_id` INT NOT NULL ,
  PRIMARY KEY (`user_id`, `city_id`) ,
  INDEX `fk_Utilisateur_Ville_Ville` (`city_id` ASC) ,
  INDEX `fk_Utilisateur_Ville_Utilisateur` (`user_id` ASC) ,
  CONSTRAINT `fk_Utilisateur_Ville_Ville`
    FOREIGN KEY (`city_id` )
    REFERENCES `City` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Utilisateur_Ville_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Drink`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Drink` ;

CREATE  TABLE IF NOT EXISTS `Drink` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `place` VARCHAR(100) NOT NULL ,
  `address` VARCHAR(100) ,
  `day` DATE NOT NULL ,
  `hour` TIME NOT NULL ,
  `kind` ENUM('drink', 'talk') NOT NULL DEFAULT 'drink' ,
  `description` TEXT NOT NULL ,
  `map` VARCHAR(256) NULL ,
  `user_id` INT NOT NULL ,
  `city_id` INT NOT NULL ,
  `latitude` DECIMAL(9,6) ,
  `longitude` DECIMAL(9,6) ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Drinks_Utilisateur` (`user_id` ASC) ,
  INDEX `fk_Drinks_Ville` (`city_id` ASC) ,
  CONSTRAINT `fk_Drinks_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Drinks_Ville`
    FOREIGN KEY (`city_id` )
    REFERENCES `City` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Drink_Participation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Drink_Participation` ;

CREATE  TABLE IF NOT EXISTS `Drink_Participation` (
  `drink_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  `percentage` INT NOT NULL ,
  `reminder` INT NOT NULL ,
  PRIMARY KEY (`drink_id`, `user_id`) ,
  INDEX `fk_Drink_Participation_Drink` (`drink_id` ASC) ,
  INDEX `fk_Drink_Participation_Utilisateur` (`user_id` ASC) ,
  CONSTRAINT `fk_Drink_Participation_Drink`
    FOREIGN KEY (`drink_id` )
    REFERENCES `Drink` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Drink_Participation_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Drink_Comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Drink_Comment` ;

CREATE  TABLE IF NOT EXISTS `Drink_Comment` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created_at` DATETIME ,
  `content` TEXT NOT NULL ,
  `drink_id` INT NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Drink_Commentaire_Drink` (`drink_id` ASC) ,
  INDEX `fk_Drink_Commentaire_Utilisateur` (`user_id` ASC) ,
  CONSTRAINT `fk_Drink_Commentaire_Drink`
    FOREIGN KEY (`drink_id` )
    REFERENCES `Drink` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Drink_Commentaire_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Article` ;

CREATE  TABLE IF NOT EXISTS `Article` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created_at` BIGINT NOT NULL ,
  `title` VARCHAR(60) NOT NULL ,
  `content` TEXT NOT NULL ,
  `published` TINYINT(1)  NOT NULL ,
  `user_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Articles_Utilisateur` (`user_id` ASC) ,
  CONSTRAINT `fk_Articles_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Article_Comment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Article_Comment` ;

CREATE  TABLE IF NOT EXISTS `Article_Comment` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `created_at` BIGINT NOT NULL ,
  `content` TEXT NOT NULL ,
  `user_id` INT NOT NULL ,
  `article_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Article_Commentaire_Utilisateur` (`user_id` ASC) ,
  INDEX `fk_Article_Commentaire_Article` (`article_id` ASC) ,
  CONSTRAINT `fk_Article_Commentaire_Utilisateur`
    FOREIGN KEY (`user_id` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Article_Commentaire_Article`
    FOREIGN KEY (`article_id` )
    REFERENCES `Article` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
