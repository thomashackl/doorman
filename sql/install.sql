CREATE TABLE IF NOT EXISTS `doormanplugin` (
    `config_id` VARCHAR(32) NOT NULL,
    `institute_id` VARCHAR(32) NOT NULL REFERENCES `Institute`.`Institut_id`,
    `set_admission_binding` TINYINT(1) NOT NULL DEFAULT 0,
    `disable_moving_up` TINYINT(1) NOT NULL DEFAULT 0,
    `disable_waitlist` TINYINT(1) NOT NULL DEFAULT 0,
    `daysbefore` INT NOT NULL DEFAULT 0,
    `mkdate` INT NOT NULL,
    `chdate` INT NOT NULL,
    PRIMARY KEY (`config_id`),
    INDEX `institute` (`institute_id`)
) COLLATE=latin1_german1_ci CHARACTER SET latin1;