SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `aperophp` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin ;
USE `aperophp` ;

-- -----------------------------------------------------
-- Table `aperophp`.`Villes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Villes` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Villes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nom_UNIQUE` (`nom` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Utilisateurs`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Utilisateurs` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Utilisateurs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(80) NULL ,
  `prenom` VARCHAR(80) NULL ,
  `identifiants` VARCHAR(80) NOT NULL ,
  `mdp` VARCHAR(64) NOT NULL ,
  `actif` TINYINT(1)  NOT NULL ,
  `jeton` VARCHAR(64) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Utilisateur_Ville`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Utilisateur_Ville` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Utilisateur_Ville` (
  `id_utilisateur` INT NOT NULL ,
  `id_ville` INT NOT NULL ,
  PRIMARY KEY (`id_utilisateur`, `id_ville`) ,
  INDEX `fk_Utilisateur_Ville_Ville` (`id_ville` ASC) ,
  INDEX `fk_Utilisateur_Ville_Utilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_Utilisateur_Ville_Ville`
    FOREIGN KEY (`id_ville` )
    REFERENCES `aperophp`.`Villes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Utilisateur_Ville_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Aperos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Aperos` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Aperos` (
  `id` INT NOT NULL ,
  `lieu` VARCHAR(100) NOT NULL ,
  `date` DATE NOT NULL ,
  `heure` INT NOT NULL ,
  `genre` ENUM('apero', 'conference') NOT NULL DEFAULT 'apero' ,
  `description` TEXT NOT NULL ,
  `carte` VARCHAR(256) NULL ,
  `id_utilisateur` INT NOT NULL ,
  `id_ville` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Aperos_Utilisateur` (`id_utilisateur` ASC) ,
  INDEX `fk_Aperos_Ville` (`id_ville` ASC) ,
  CONSTRAINT `fk_Aperos_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Aperos_Ville`
    FOREIGN KEY (`id_ville` )
    REFERENCES `aperophp`.`Villes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Apero_Participation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Apero_Participation` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Apero_Participation` (
  `id_apero` INT NOT NULL ,
  `id_utilisateur` INT NOT NULL ,
  `pourcentage` INT NOT NULL ,
  `rappel` INT NOT NULL ,
  PRIMARY KEY (`id_apero`, `id_utilisateur`) ,
  INDEX `fk_Apero_Participation_Apero` (`id_apero` ASC) ,
  INDEX `fk_Apero_Participation_Utilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_Apero_Participation_Apero`
    FOREIGN KEY (`id_apero` )
    REFERENCES `aperophp`.`Aperos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Apero_Participation_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Apero_Commentaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Apero_Commentaire` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Apero_Commentaire` (
  `id` INT NOT NULL ,
  `temps` BIGINT NOT NULL ,
  `contenu` TEXT NOT NULL ,
  `id_apero` INT NOT NULL ,
  `id_utilisateur` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Apero_Commentaire_Apero` (`id_apero` ASC) ,
  INDEX `fk_Apero_Commentaire_Utilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_Apero_Commentaire_Apero`
    FOREIGN KEY (`id_apero` )
    REFERENCES `aperophp`.`Aperos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Apero_Commentaire_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Articles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Articles` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Articles` (
  `id` INT NOT NULL ,
  `temps` BIGINT NOT NULL ,
  `titre` VARCHAR(60) NOT NULL ,
  `contenu` TEXT NOT NULL ,
  `parrution` TINYINT(1)  NOT NULL ,
  `id_utilisateur` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Articles_Utilisateur` (`id_utilisateur` ASC) ,
  CONSTRAINT `fk_Articles_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `aperophp`.`Article_Commentaire`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `aperophp`.`Article_Commentaire` ;

CREATE  TABLE IF NOT EXISTS `aperophp`.`Article_Commentaire` (
  `id` INT NOT NULL ,
  `temps` BIGINT NOT NULL ,
  `contenu` TEXT NOT NULL ,
  `id_utilisateur` INT NOT NULL ,
  `id_article` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_Article_Commentaire_Utilisateur` (`id_utilisateur` ASC) ,
  INDEX `fk_Article_Commentaire_Article` (`id_article` ASC) ,
  CONSTRAINT `fk_Article_Commentaire_Utilisateur`
    FOREIGN KEY (`id_utilisateur` )
    REFERENCES `aperophp`.`Utilisateurs` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Article_Commentaire_Article`
    FOREIGN KEY (`id_article` )
    REFERENCES `aperophp`.`Articles` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
