CREATE TABLE
	IF NOT EXISTS `#__addresses` (
		`id` int (11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`catid` int (11) UNSIGNED NOT NULL DEFAULT 0,
		`ordering` INT (11) NOT NULL,
		`title` VARCHAR(128) NOT NULL,
		`address` VARCHAR(255) NOT NULL,
		`postcode` VARCHAR(10) NOT NULL,
		`city` VARCHAR(100) NOT NULL,
		`country` VARCHAR(120) NOT NULL,
		`state` INT (11) NOT NULL DEFAULT 1,
		`created_by` INT (11) NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`)
	) ENGINE = InnoDB COMMENT = 'Addresses table' DEFAULT COLLATE = utf8_general_ci;