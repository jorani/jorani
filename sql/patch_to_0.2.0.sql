-- ---------------------------------------------------
-- Jorani Schema upgrade to 0.2.0
-- 
-- @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- New features
--      * Early versions didn't have the weekly duration of a contract
--      * Daily duration on a contract
--      * Description of an entitled days
DELIMITER $$
CREATE PROCEDURE sp_add_new_col()
    SQL SECURITY INVOKER
BEGIN
        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='contracts' AND column_name = 'weekly_duration'
        ) THEN
                ALTER TABLE `contracts` ADD COLUMN `weekly_duration` int(11) DEFAULT NULL COMMENT 'Approximate duration of work per week (in minutes)';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='contracts' AND column_name = 'daily_duration'
        ) THEN
                ALTER TABLE `contracts` ADD COLUMN `daily_duration` int(11) DEFAULT NULL COMMENT 'Approximate duration of work per day (in minutes)';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='entitleddays' AND column_name = 'description'
        ) THEN
                ALTER TABLE `entitleddays` ADD COLUMN `description` text DEFAULT NULL COMMENT 'Description of a credit / debit';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='users' AND column_name = 'ldap_path'
        ) THEN
                ALTER TABLE `users` ADD COLUMN `ldap_path` varchar(1024) DEFAULT NULL COMMENT 'LDAP Path for complex authentication schemes';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='users' AND column_name = 'active'
        ) THEN
                ALTER TABLE `users` ADD COLUMN `active` bool DEFAULT TRUE COMMENT 'Is user active';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='users' AND column_name = 'timezone'
        ) THEN
                ALTER TABLE `users` ADD COLUMN `timezone` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Timezone of user';
        END IF;

        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='users' AND column_name = 'calendar'
        ) THEN
                ALTER TABLE `users` ADD COLUMN `calendar` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'External Calendar address';
        END IF;

END$$
DELIMITER ;

CALL sp_add_new_col();
DROP PROCEDURE sp_add_new_col;

-- Fixing default value for strict configuration
ALTER TABLE `entitleddays` MODIFY COLUMN `contract` int(11) DEFAULT NULL;
ALTER TABLE `entitleddays` MODIFY COLUMN `employee` int(11) DEFAULT NULL;
ALTER TABLE `contracts` MODIFY COLUMN `weekly_duration` int(11) DEFAULT NULL;

-- Fix procedures security
DROP FUNCTION IF EXISTS `GetAncestry`;
DELIMITER $$
CREATE FUNCTION `GetAncestry`(GivenID INT) RETURNS varchar(1024) CHARSET utf8
    NOT DETERMINISTIC
    READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
    DECLARE rv VARCHAR(1024);
    DECLARE cm CHAR(1);
    DECLARE ch INT;

    SET rv = '';
    SET cm = '';
    SET ch = GivenID;
    WHILE ch > 0 DO
        SELECT IFNULL(parent_id,-1) INTO ch FROM
        (SELECT parent_id FROM organization WHERE id = ch) A;
        IF ch > 0 THEN
            SET rv = CONCAT(rv,cm,ch);
            SET cm = ',';
        END IF;
    END WHILE;
    RETURN rv;
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS `GetFamilyTree`;
DELIMITER $$
CREATE FUNCTION `GetFamilyTree`(`GivenID` INT) RETURNS varchar(1024) CHARSET utf8
    NOT DETERMINISTIC
    READS SQL DATA
    SQL SECURITY INVOKER
BEGIN

    DECLARE rv,q,queue,queue_children VARCHAR(1024);
    DECLARE queue_length,front_id,pos INT;

    SET rv = '';
    SET queue = GivenID;
    SET queue_length = 1;

    WHILE queue_length > 0 DO
        SET front_id = FORMAT(queue,0);
        IF queue_length = 1 THEN
            SET queue = '';
        ELSE
            SET pos = LOCATE(',',queue) + 1;
            SET q = SUBSTR(queue,pos);
            SET queue = q;
        END IF;
        SET queue_length = queue_length - 1;

        SELECT IFNULL(qc,'') INTO queue_children
        FROM (SELECT GROUP_CONCAT(id) qc
        FROM organization WHERE parent_id = front_id) A;

        IF LENGTH(queue_children) = 0 THEN
            IF LENGTH(queue) = 0 THEN
                SET queue_length = 0;
            END IF;
        ELSE
            IF LENGTH(rv) = 0 THEN
                SET rv = queue_children;
            ELSE
                SET rv = CONCAT(rv,',',queue_children);
            END IF;
            IF LENGTH(queue) = 0 THEN
                SET queue = queue_children;
            ELSE
                SET queue = CONCAT(queue,',',queue_children);
            END IF;
            SET queue_length = LENGTH(queue) - LENGTH(REPLACE(queue,',','')) + 1;
        END IF;
    END WHILE;
    RETURN rv;
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS `GetParentIDByID`;
DELIMITER $$
CREATE FUNCTION `GetParentIDByID`(GivenID INT) RETURNS int(11)
    NOT DETERMINISTIC
    READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
    DECLARE rv INT;

    SELECT IFNULL(parent_id,-1) INTO rv FROM
    (SELECT parent_id FROM organization WHERE id = GivenID) A;
    RETURN rv;
END$$
DELIMITER ;
