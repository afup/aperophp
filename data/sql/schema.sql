SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

--CREATE SCHEMA IF NOT EXISTS `aperophp` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;

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
  `email` VARCHAR(80) NOT NULL ,
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
  `username` VARCHAR(80) NOT NULL ,
  `password` VARCHAR(80) NOT NULL ,
  `active` TINYINT(1) NOT NULL DEFAULT '1' ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `User_City`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `User_City` ;

CREATE  TABLE IF NOT EXISTS `User_City` (
  `id_user` INT NOT NULL ,
  `id_city` INT NOT NULL ,
  PRIMARY KEY (`id_user`, `id_city`) ,
  INDEX `fk_Utilisateur_Ville_Ville` (`id_city` ASC) ,
  INDEX `fk_Utilisateur_Ville_Utilisateur` (`id_user` ASC) ,
  CONSTRAINT `fk_Utilisateur_Ville_Ville`
    FOREIGN KEY (`id_city` )
    REFERENCES `City` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Utilisateur_Ville_Utilisateur`
    FOREIGN KEY (`id_user` )
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
  `latitude` DECIMAL(9,6) ,
  `longitude` DECIMAL(9,6) ,
  `id_user` INT NOT NULL ,
  `id_city` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Aperos_Utilisateur` (`id_user` ASC) ,
  INDEX `fk_Aperos_Ville` (`id_city` ASC) ,
  CONSTRAINT `fk_Aperos_Utilisateur`
    FOREIGN KEY (`id_user` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Aperos_Ville`
    FOREIGN KEY (`id_city` )
    REFERENCES `City` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Drink_Participation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `Drink_Participation` ;

CREATE  TABLE IF NOT EXISTS `Drink_Participation` (
  `id_drink` INT NOT NULL ,
  `id_user` INT NOT NULL ,
  `percentage` INT NOT NULL ,
  `reminder` INT NOT NULL ,
  PRIMARY KEY (`id_drink`, `id_user`) ,
  INDEX `fk_Apero_Participation_Apero` (`id_drink` ASC) ,
  INDEX `fk_Apero_Participation_Utilisateur` (`id_user` ASC) ,
  CONSTRAINT `fk_Apero_Participation_Apero`
    FOREIGN KEY (`id_drink` )
    REFERENCES `Drink` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Apero_Participation_Utilisateur`
    FOREIGN KEY (`id_user` )
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
  `created_at` BIGINT NOT NULL ,
  `content` TEXT NOT NULL ,
  `id_drink` INT NOT NULL ,
  `id_user` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Apero_Commentaire_Apero` (`id_drink` ASC) ,
  INDEX `fk_Apero_Commentaire_Utilisateur` (`id_user` ASC) ,
  CONSTRAINT `fk_Apero_Commentaire_Apero`
    FOREIGN KEY (`id_drink` )
    REFERENCES `Drink` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Apero_Commentaire_Utilisateur`
    FOREIGN KEY (`id_user` )
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
  `id_user` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Articles_Utilisateur` (`id_user` ASC) ,
  CONSTRAINT `fk_Articles_Utilisateur`
    FOREIGN KEY (`id_user` )
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
  `id_user` INT NOT NULL ,
  `id_article` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Article_Commentaire_Utilisateur` (`id_user` ASC) ,
  INDEX `fk_Article_Commentaire_Article` (`id_article` ASC) ,
  CONSTRAINT `fk_Article_Commentaire_Utilisateur`
    FOREIGN KEY (`id_user` )
    REFERENCES `User` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Article_Commentaire_Article`
    FOREIGN KEY (`id_article` )
    REFERENCES `Article` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
