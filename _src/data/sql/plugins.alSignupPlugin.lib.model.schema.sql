
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- alsignup_newsletter_adverts
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `alsignup_newsletter_adverts`;


CREATE TABLE `alsignup_newsletter_adverts`
(
	`newsletter_adverts_id` INTEGER  NOT NULL AUTO_INCREMENT,
	`advertised` VARCHAR(30)  NOT NULL,
	PRIMARY KEY (`newsletter_adverts_id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- alsignup_newsletter_signups
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `alsignup_newsletter_signups`;


CREATE TABLE `alsignup_newsletter_signups`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`first_name` VARCHAR(20)  NOT NULL,
	`surname` VARCHAR(20)  NOT NULL,
	`email` VARCHAR(100)  NOT NULL,
	`activation_key` VARCHAR(100)  NOT NULL,
	`activated` TINYINT default 0 NOT NULL,
	`newsletter_adverts_id` INTEGER  NOT NULL,
	`created_at` DATETIME  NOT NULL,
	`updated_at` DATETIME  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `alsignup_newsletter_signups_FI_1` (`newsletter_adverts_id`),
	CONSTRAINT `alsignup_newsletter_signups_FK_1`
		FOREIGN KEY (`newsletter_adverts_id`)
		REFERENCES `alsignup_newsletter_adverts` (`newsletter_adverts_id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
