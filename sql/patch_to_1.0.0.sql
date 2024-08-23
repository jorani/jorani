-- ---------------------------------------------------
-- Jorani Schema upgrade to 1.0.0
--
-- @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- Clean model from tables that are now into 3rd party applications
-- Fix default values for database
-- Convert to utf8mb4_unicode_ci
-- Improve comments
-- Add columns for features of v1.0.0

-- Task management feature is now part of a 3rd party application
DROP TABLE IF EXISTS `activities`;
DROP TABLE IF EXISTS `activities_employee`;
DROP TABLE IF EXISTS `tasks`;
DROP TABLE IF EXISTS `time`;
DROP TABLE IF EXISTS `settings`;

-- Harmonize the charsets and engines
ALTER TABLE `actions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='List of possible actions';
ALTER TABLE `contracts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='List of contracts (common settings between employees)';
ALTER TABLE `dayoffs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='List of non working days';
ALTER TABLE `entitleddays` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Add or sub entitlement for employees or contracts';
ALTER TABLE `types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='List of leave types (LoV table)';
ALTER TABLE `users` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='List of employees / users having access to Jorani';

ALTER TABLE `leaves` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Leave requests';
ALTER TABLE `organization` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Tree of the organization';
ALTER TABLE `overtime` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Overtime worked (extra time)';
ALTER TABLE `positions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Position (job position) in the organization';
ALTER TABLE `roles` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Roles in the application (system table)';
ALTER TABLE `status` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Status of the Leave Request (system table)';
ALTER TABLE `parameters` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci, COMMENT='Parameters can be global or specific to an object';

ALTER TABLE `delegations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `excluded_types` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `leaves_history` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `org_lists_employees` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `org_lists` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE oauth_clients CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `oauth_applications` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Enlarge the login field so as to allow using an email address
ALTER TABLE `users` 
    CHANGE `login` `login` VARCHAR(255) NULL DEFAULT NULL 
    COMMENT 'Identifier used by a user so as to login (can be an email if coupled with AD)';

-- Profile picture
DELIMITER $$
CREATE PROCEDURE sp_add_profile_picture_users()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'picture'
    ) THEN
        ALTER TABLE `users` ADD `picture` BLOB NULL COMMENT 'Profile picture of user for tabular calendar';
    END IF;
END$$
DELIMITER ;
CALL sp_add_profile_picture_users();
DROP PROCEDURE sp_add_profile_picture_users;

-- Supporting document for a leave request
DELIMITER $$
CREATE PROCEDURE sp_add_supporting_doc_leaves()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'leaves' AND column_name = 'document'
    ) THEN
        ALTER TABLE `leaves` ADD `document` BLOB NULL COMMENT 'Optional supporting document' AFTER `comments`;
        ALTER TABLE `leaves_history` ADD COLUMN `document` BLOB NULL COMMENT 'Optional supporting document' AFTER `comments`;
    END IF;
END$$
DELIMITER ;
CALL sp_add_supporting_doc_leaves();
DROP PROCEDURE sp_add_supporting_doc_leaves;

-- Random hash for public feeds
DELIMITER $$
CREATE PROCEDURE sp_add_random_hash_users()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'random_hash'
    ) THEN
        ALTER TABLE `users` ADD `random_hash` varchar(24) DEFAULT NULL COMMENT 'Random hash for public feeds (eg. ICS URL)';
    END IF;
END$$
DELIMITER ;
CALL sp_add_random_hash_users();
DROP PROCEDURE sp_add_random_hash_users;

-- Insert a random hash for all list_users
UPDATE `users` SET
`random_hash` = concat(
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@lid)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed:=round(rand(@seed)*4294967296))*62+1, 1),
  substring('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', rand(@seed)*62+1, 1)
);

-- User properties (for Jorani to become a SAML identity provider)
DELIMITER $$
CREATE PROCEDURE sp_add_user_properties_users()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'user_properties'
    ) THEN
        ALTER TABLE `users` ADD `user_properties` TEXT NULL DEFAULT NULL COMMENT 'Extended properties encoded in JSON';
    END IF;
END$$
DELIMITER ;
CALL sp_add_user_properties_users();
DROP PROCEDURE sp_add_user_properties_users;

-- Entity ID in table `parameters` (for user/table scope values)
DELIMITER $$
CREATE PROCEDURE sp_add_entity_id_parameters()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'parameters' AND column_name = 'entity_id'
    ) THEN
        ALTER TABLE `parameters` ADD `entity_id` TEXT NULL DEFAULT NULL COMMENT 'Entity ID (eg. user id) to which the parameter is applied';
    END IF;
END$$
DELIMITER ;
CALL sp_add_entity_id_parameters();
DROP PROCEDURE sp_add_entity_id_parameters;

-- An entitlement can be linked to an overtime request
DELIMITER $$
CREATE PROCEDURE sp_add_overtime_link_entitleddays()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'entitleddays' AND column_name = 'overtime'
    ) THEN
        ALTER TABLE `entitleddays` ADD `overtime` int(11) DEFAULT NULL COMMENT 'Link to an overtime request' AFTER `employee`;
    END IF;
END$$
DELIMITER ;
CALL sp_add_overtime_link_entitleddays();
DROP PROCEDURE sp_add_overtime_link_entitleddays;

-- Migrate accepted overtime requests into the new system of entitlement for overtime
-- The catch of this query is that it doesn't work for employees without a contract
INSERT INTO entitleddays(employee, overtime, startdate, enddate, type, days, description)
select 	o.employee,
		o.id,
		CAST(CONCAT(year(o.date), '-', REPLACE(c.startentdate, '/', '-')) AS DATE) as sd,
		CAST(CONCAT(year(o.date), '-', REPLACE(c.endentdate, '/', '-')) AS DATE) as ed,
        0,
        o.duration,
        CONCAT('_MIGRATION_: ', o.cause)
from overtime o
inner join users u on o.employee = u.id
inner join contracts c on u.contract = c.id
left outer join entitleddays e on o.id = e.overtime
where e.id is null
and o.status = 3;

-- Fix bug on GetFamilyTree procedure
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
        IF queue_length = 1 THEN
            SET front_id = CAST(queue AS INT);
            SET queue = '';
        ELSE
            SET pos = LOCATE(',',queue);
            SET front_id = CAST(SUBSTR(queue, 1, pos-1) AS INT);
            SET q = SUBSTR(queue,pos + 1); 
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
