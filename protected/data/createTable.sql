CREATE  TABLE IF NOT EXISTS `#__osefirewall_virusVersion` (
  `version_id` INT(11) NOT NULL AUTO_INCREMENT ,
  `version` VARCHAR(200) NOT NULL ,
  `plugin` VARCHAR(200) NOT NULL ,
  PRIMARY KEY (`version_id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE	TABLE IF NOT EXISTS `#__osefirewall_updateLog` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `time` DATE NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_referers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `referer_url` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_pages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `page_url` TEXT NOT NULL ,
  `action` TINYINT(1) NOT NULL ,
  `visits` INT(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_acl` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(300) NOT NULL ,
  `status` TINYINT(1) NOT NULL ,
  `datetime` DATETIME NOT NULL ,
  `score` TINYINT(3) NOT NULL ,
  `country_code` CHAR(2) NULL DEFAULT NULL ,
  `host` VARCHAR(300) NULL DEFAULT NULL ,
  `notified` TINYINT(1) NULL DEFAULT NULL ,
  `referers_id` INT(11) NOT NULL ,
  `pages_id` INT(11) NOT NULL ,
  `visits` INT(11) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx1_oseacl` (`referers_id` ASC) ,
  INDEX `idx2_oseacl` (`pages_id` ASC) ,
  CONSTRAINT `fk1_oseacl`
    FOREIGN KEY (`referers_id` )
    REFERENCES `#__osefirewall_referers` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk2_oseacl`
    FOREIGN KEY (`pages_id` )
    REFERENCES `#__osefirewall_pages` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_attacktype` (
  `id` TINYINT(2) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NOT NULL ,
  `tag` VARCHAR(5) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_detattacktype` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `attacktypeid` TINYINT(3) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `idx1_detattacktype` (`attacktypeid` ASC) ,
  CONSTRAINT `fk1_detattacktype`
    FOREIGN KEY (`attacktypeid` )
    REFERENCES `#__osefirewall_attacktype` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_vars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `keyname` VARCHAR(300) NOT NULL ,
  `status` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_detcontent` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `content` LONGTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = latin1;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_detcontdetail` (
  `detattacktype_id` INT(11) NOT NULL ,
  `detcontent_id` INT(11) NOT NULL ,
  `rule_id` INT(11) NOT NULL ,
  `var_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`detattacktype_id`) ,
  INDEX `idx1_detcontdetail` (`var_id` ASC) ,
  INDEX `idx2_detcontdetail` (`detcontent_id` ASC) ,
  CONSTRAINT `fk1_detcontdetail`
    FOREIGN KEY (`var_id` )
    REFERENCES `#__osefirewall_vars` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk2__detcontdetail`
    FOREIGN KEY (`detattacktype_id` )
    REFERENCES `#__osefirewall_detattacktype` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk3__detcontdetail`
    FOREIGN KEY (`detcontent_id` )
    REFERENCES `#__osefirewall_detcontent` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_detected` (
  `acl_id` INT(11) NOT NULL ,
  `detattacktype_id` INT(11) NOT NULL ,
  PRIMARY KEY (`acl_id`, `detattacktype_id`) ,
  INDEX `idx1_detected` (`acl_id` ASC) ,
  INDEX `idx2_detected` (`detattacktype_id` ASC) ,
  CONSTRAINT `fk1__detected`
    FOREIGN KEY (`acl_id` )
    REFERENCES `#__osefirewall_acl` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk2__detected`
    FOREIGN KEY (`detattacktype_id` )
    REFERENCES `#__osefirewall_detattacktype` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_filters` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `filter` TEXT NOT NULL ,
  `action` TINYINT(1) NOT NULL DEFAULT '1' ,
  `attacktype` VARCHAR(45) NOT NULL ,
  `impact` TINYINT(3) NOT NULL ,
  `description` VARCHAR(400) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_filters_bk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `filter` TEXT NOT NULL ,
  `action` TINYINT(1) NOT NULL DEFAULT '1' ,
  `attacktype` VARCHAR(45) NOT NULL ,
  `impact` TINYINT(3) NOT NULL ,
  `description` VARCHAR(400) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_iptable` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ip32_start` VARCHAR(10) NOT NULL ,
  `ip32_end` VARCHAR(10) NOT NULL ,
  `acl_id` INT(11) NOT NULL ,
  `iptype` TINYINT(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`, `acl_id`) ,
  INDEX `idx1__iptable` (`acl_id` ASC) ,
  CONSTRAINT `fk1__iptable`
    FOREIGN KEY (`acl_id` )
    REFERENCES `#__osefirewall_acl` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_iptable_tmp` (
  `id` INT(20) NOT NULL AUTO_INCREMENT ,
  `ip32_start` VARCHAR(10) NOT NULL ,
  `last_session_request` TEXT NULL DEFAULT NULL ,
  `total_session_request` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_sfschecked` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ip32_start` VARCHAR(10) NOT NULL ,
  `ischecked` TINYINT(1) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_signatures` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `signature` TEXT NOT NULL ,
  `action` TINYINT(1) NOT NULL DEFAULT '1' ,
  `attacktype` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_signatures_bk` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `signature` TEXT NOT NULL ,
  `action` TINYINT(1) NOT NULL DEFAULT '1' ,
  `attacktype` VARCHAR(10) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__ose_app_admin` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `user_id` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__ose_app_email` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `app` VARCHAR(20) NOT NULL ,
  `subject` TEXT NOT NULL ,
  `body` TEXT NOT NULL ,
  `type` VARCHAR(20) NOT NULL ,
  `params` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

ALTER TABLE `#__ose_app_email` ENGINE = InnoDB;

CREATE  TABLE IF NOT EXISTS `#__ose_app_adminrecemail` (
  `email_id` INT(11) NOT NULL ,
  `admin_id` INT(11) NOT NULL ,
  PRIMARY KEY (`email_id`, `admin_id`) ,
  INDEX `idx1_adminrecemail` (`admin_id` ASC) ,
  INDEX `idx2_adminrecemail` (`email_id` ASC) ,
  CONSTRAINT `fk1__adminrecemail`
    FOREIGN KEY (`email_id` )
    REFERENCES `#__ose_app_email` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `fk2_adminrecemail`
    FOREIGN KEY (`admin_id` )
    REFERENCES `#__ose_app_admin` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__ose_app_geoip` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `ip32_start` TEXT NOT NULL ,
  `ip32_end` TEXT NOT NULL ,
  `country_code` CHAR(2) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `#__ose_secConfig` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `key` TEXT NOT NULL ,
  `value` TEXT NOT NULL ,
  `type` VARCHAR(20) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `#__osefirewall_basicrules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule` text NOT NULL,
  `action` tinyint(1) NOT NULL DEFAULT '1',
  `attacktype` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__osefirewall_basicrules_bk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rule` text NOT NULL,
  `action` tinyint(1) NOT NULL DEFAULT '1',
  `attacktype` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_files` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `filename` TEXT NOT NULL ,
  `ext` VARCHAR(20) NOT NULL ,
  `type` CHAR(1) NOT NULL ,
  `checked` TINYINT(1) NULL ,
  `datechecked` DATE NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__osefirewall_vstypes` (
  `id` TINYINT(3) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_vspatterns` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `patterns` TEXT NOT NULL ,
  `type_id` TINYINT(3) NOT NULL ,
  `confidence` TINYINT(3) NOT NULL,
  PRIMARY KEY (`id`) ,
  INDEX `osefirewall_vspatterns_idx1` (`type_id` ASC) ,
  CONSTRAINT `osefirewall_vspatterns_fk1`
    FOREIGN KEY (`type_id` )
    REFERENCES `#__osefirewall_vstypes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE  TABLE IF NOT EXISTS `#__osefirewall_malware` (
  `file_id` INT(11) NOT NULL ,
  `pattern_id` INT(11) NOT NULL ,
  PRIMARY KEY (`file_id`, `pattern_id`) ,
  INDEX `osefirewall_malware_idx1` (`pattern_id` ASC) ,
  INDEX `osefirewall_malware_idx2` (`file_id` ASC))
ENGINE = InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__osefirewall_logs` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `date` datetime DEFAULT NULL,
			  `comp` varchar(3) NOT NULL,
			  `status` text NOT NULL,
			  PRIMARY KEY (`id`)
)
ENGINE = InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__osefirewall_sfschecked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip32_start` varchar(10) NOT NULL,
  `ischecked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
