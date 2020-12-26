
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

#-----------------------------------------------------------------------------
#-- milkshakes
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `milkshakes`;


CREATE TABLE `milkshakes`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(100)  NOT NULL,
	`image_url` VARCHAR(255)  NOT NULL,
	`thumb_url` VARCHAR(255)  NOT NULL,
	`calories` FLOAT  NOT NULL,
	`created_at` DATETIME  NOT NULL,
	`updated_at` DATETIME  NOT NULL,
	`url_slug` VARCHAR(100)  NOT NULL,
	`views` INTEGER default 0,
	PRIMARY KEY (`id`),
	KEY `milkshake_name_index`(`name`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- flavors
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `flavors`;


CREATE TABLE `flavors`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(20)  NOT NULL,
	`created_at` DATETIME  NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- milkshake_flavors
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `milkshake_flavors`;


CREATE TABLE `milkshake_flavors`
(
	`milkshake_id` INTEGER  NOT NULL,
	`flavor_id` INTEGER  NOT NULL,
	PRIMARY KEY (`milkshake_id`,`flavor_id`),
	INDEX `milkshake_flavors_FI_1` (`flavor_id`),
	CONSTRAINT `milkshake_flavors_FK_1`
		FOREIGN KEY (`flavor_id`)
		REFERENCES `flavors` (`id`)
		ON DELETE CASCADE,
	CONSTRAINT `milkshake_flavors_FK_2`
		FOREIGN KEY (`milkshake_id`)
		REFERENCES `milkshakes` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- store_locations
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `store_locations`;


CREATE TABLE `store_locations`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`address1` VARCHAR(100)  NOT NULL,
	`address2` VARCHAR(100)  NOT NULL,
	`address3` VARCHAR(50)  NOT NULL,
	`postcode` VARCHAR(8)  NOT NULL,
	`city` VARCHAR(50)  NOT NULL,
	`country` VARCHAR(50)  NOT NULL,
	`phone` VARCHAR(20)  NOT NULL,
	`fax` VARCHAR(20)  NOT NULL,
	PRIMARY KEY (`id`)
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- vacancies
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `vacancies`;


CREATE TABLE `vacancies`
(
	`id` INTEGER  NOT NULL AUTO_INCREMENT,
	`locations_id` INTEGER  NOT NULL,
	`closing_date` DATETIME  NOT NULL,
	PRIMARY KEY (`id`),
	INDEX `vacancies_FI_1` (`locations_id`),
	CONSTRAINT `vacancies_FK_1`
		FOREIGN KEY (`locations_id`)
		REFERENCES `store_locations` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

#-----------------------------------------------------------------------------
#-- vacancies_i18n
#-----------------------------------------------------------------------------

DROP TABLE IF EXISTS `vacancies_i18n`;


CREATE TABLE `vacancies_i18n`
(
	`id` INTEGER  NOT NULL,
	`culture` VARCHAR(7)  NOT NULL,
	`position` VARCHAR(30)  NOT NULL,
	`position_description` VARCHAR(100)  NOT NULL,
	PRIMARY KEY (`id`,`culture`),
	CONSTRAINT `vacancies_i18n_FK_1`
		FOREIGN KEY (`id`)
		REFERENCES `vacancies` (`id`)
		ON DELETE CASCADE
)Type=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
