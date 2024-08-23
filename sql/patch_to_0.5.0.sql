-- ---------------------------------------------------
-- Jorani Schema upgrade to 0.5.0
-- 
-- @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- New features:
--      * Bug fix on table dayoffs
--      * Users table now supports Regional translations (e.g. 'en' to 'en_GB').
--      * Add the possibility to exclude leave types from a contract.
--      * Define a default leave type for a contract (overwrite default type set in config file).
--      * History of changes on leave requests table.
--      * Duration of leave and overtime requests were rounded to 2 decimals, now 3 decimals.
--      * Option to deduct or not day offs when computing leave balance.

ALTER TABLE `dayoffs` MODIFY `title` varchar(128) CHARACTER SET utf8;

ALTER TABLE `users` MODIFY `language` varchar(5);
ALTER TABLE `users` MODIFY `position` int(11) DEFAULT NULL;
ALTER TABLE `users` MODIFY `organization` int(11) DEFAULT 0;

ALTER TABLE `leaves` MODIFY `duration` decimal(10,3) DEFAULT NULL;

ALTER TABLE `overtime` MODIFY `duration` decimal(10,3) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `leaves_history` (
  `id` int(11) NOT NULL,
  `startdate` date DEFAULT NULL,
  `enddate` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `employee` int(11) DEFAULT NULL,
  `cause` text,
  `startdatetype` varchar(12) DEFAULT NULL,
  `enddatetype` varchar(12) DEFAULT NULL,
  `duration` decimal(10,2) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `change_id` int(11) NOT NULL AUTO_INCREMENT,
  `change_type` int(11) NOT NULL,
  `changed_by` int(11) NOT NULL,
  `change_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`change_id`),
  KEY `changed_by` (`changed_by`),
  KEY `change_date` (`change_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='List of changes in leave requests table' COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `excluded_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id of exclusion',
  `contract_id` int(11) NOT NULL COMMENT 'Id of contract',
  `type_id` int(11) NOT NULL COMMENT 'Id of leave ype to be excluded to the contract',
  PRIMARY KEY (`id`),
  KEY `contract_id` (`contract_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Exclude a leave type from a contract' AUTO_INCREMENT=1 ;

DELIMITER $$
CREATE PROCEDURE sp_add_default_leave_type_contract()
    SQL SECURITY INVOKER
BEGIN
        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='contracts' AND column_name = 'default_leave_type'
        ) THEN
                ALTER TABLE `contracts` ADD `default_leave_type` INT NULL DEFAULT NULL COMMENT 'default leave type for the contract (overwrite default type set in config file).';
        END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE sp_add_deduction_days_off_for_type()
    SQL SECURITY INVOKER
BEGIN
        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='types' AND column_name = 'deduct_days_off'
        ) THEN
                ALTER TABLE `types` ADD `deduct_days_off` BOOL NOT NULL DEFAULT 0 COMMENT 'Deduct days off when computing the balance of the leave type.';
        END IF;
END$$
DELIMITER ;

CALL sp_add_default_leave_type_contract();
DROP PROCEDURE sp_add_default_leave_type_contract;

CALL sp_add_deduction_days_off_for_type();
DROP PROCEDURE sp_add_deduction_days_off_for_type;
