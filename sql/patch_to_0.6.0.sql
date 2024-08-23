-- ---------------------------------------------------
-- Jorani Schema upgrade to 0.6.0
-- 
-- @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- New features:
-- * Role admin
-- * Bug fix: allow OAuth2 client's secret to be null (case of Authorization).
-- * Table listing OAuth2 authorized application by a user.
-- * Migration to CI3: we now store the seesions into database.
-- * Users can create a custom list of employees (to be used in global/tabular calendars)
-- * Users may choose an acronym for leave types
-- * Some parameters are stored into DB on a global of user scope
-- * Add comments on leave requests depending on the status and the configuration

SET SQL_SAFE_UPDATES = 0;

-- New admin role
INSERT IGNORE INTO `roles` SET `id` = 1, `name` = 'admin';

-- List of authorized applications
ALTER TABLE `oauth_clients` MODIFY `client_secret` varchar(80) DEFAULT NULL;

CREATE TABLE IF NOT EXISTS `oauth_applications` (
  `user` int(11) NOT NULL COMMENT 'Identifier of Jorani user',
  `client_id` varchar(80) NOT NULL COMMENT 'Identifier of an application using OAuth2',
  KEY `user` (`user`),
  KEY `client_id` (`client_id`)
) COMMENT='List of allowed OAuth2 applications';

-- CI Session storage
CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(128) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
) COMMENT='CodeIgniter sessions';

-- Arbitrary list of employees
CREATE TABLE IF NOT EXISTS `org_lists` (
    `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier of a list',
    `user` INT NOT NULL COMMENT 'Identifier of Jorani user owning the list',
    `name` VARCHAR(512) NOT NULL COMMENT 'Name of the list',
PRIMARY KEY (`id`),
INDEX `org_lists_user` (`user`)
) COMMENT = 'Custom lists of employees are an alternative to organization';

CREATE TABLE IF NOT EXISTS `org_lists_employees` (
    `list` INT NOT NULL COMMENT 'Id of the list',
    `user` INT NOT NULL COMMENT 'id of an employee',
    `orderlist` INT NOT NULL COMMENT 'order in the list',
INDEX `org_list_id` (`list`)
) COMMENT = 'Children table of org_lists (custom list of employees)';

-- Acronyms for leave types
DELIMITER $$
CREATE PROCEDURE sp_add_acronym_types()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'types' AND column_name = 'acronym'
    ) THEN
        ALTER TABLE `types` ADD `acronym` VARCHAR(10) NULL DEFAULT NULL COMMENT 'Acronym of leave type' AFTER `name`;
    END IF;
END$$
DELIMITER ;
CALL sp_add_acronym_types();
DROP PROCEDURE sp_add_acronym_types;

DROP FUNCTION IF EXISTS `GetInitials`;
DELIMITER $$
CREATE FUNCTION `GetInitials`(str text, expr text) RETURNS text CHARSET utf8
    NOT DETERMINISTIC
    READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
    declare result text default '';
    declare buffer text default '';
    declare i int default 1;
    if(str is null) then
        return null;
    end if;
    set buffer = trim(str);
    while i <= length(buffer) do
        if substr(buffer, i, 1) regexp expr then
            set result = concat( result, substr( buffer, i, 1 ));
            set i = i + 1;
            while i <= length( buffer ) and substr(buffer, i, 1) regexp expr do
                set i = i + 1;
            end while;
            while i <= length( buffer ) and substr(buffer, i, 1) not regexp expr do
                set i = i + 1;
            end while;
        else
            set i = i + 1;
        end if;
    end while;
    return result;
END$$
DELIMITER ;

DROP FUNCTION IF EXISTS `GetAcronym`;
DELIMITER $$
CREATE FUNCTION `GetAcronym`(str text) RETURNS text CHARSET utf8
    NOT DETERMINISTIC
    READS SQL DATA
    SQL SECURITY INVOKER
BEGIN
    declare result text default '';
    set result = GetInitials( str, '[[:alnum:]]' );
    return result;
END$$
DELIMITER ;

update `types` SET `acronym` = GetAcronym(`name`) where (`acronym` = NULL OR `acronym` = '');

-- Parameters stored into DB on a global of user scope
CREATE TABLE IF NOT EXISTS `parameters` (
`name` VARCHAR(32) NOT NULL,
`scope` INT NOT NULL COMMENT 'Either global(0) or user(1) scope',
`value` TEXT NOT NULL COMMENT 'PHP/serialize value',
INDEX `param_name` (`name`, `scope`)
) COMMENT = 'Application parameters';

-- Comments on leave request
DELIMITER $$
CREATE PROCEDURE sp_add_comments_leave_request()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'leaves' AND column_name = 'comments'
    ) THEN
        ALTER TABLE `leaves` ADD `comments` TEXT NULL DEFAULT NULL COMMENT 'Comments on leave request';
    END IF;
END$$
DELIMITER ;
CALL sp_add_comments_leave_request();
DROP PROCEDURE sp_add_comments_leave_request;

-- Adding a field comment has an impact on leave history
DELIMITER $$
CREATE PROCEDURE sp_add_comments_leave_history()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'leaves_history' AND column_name = 'comments'
    ) THEN
        ALTER TABLE `leaves_history` ADD `comments` TEXT NULL DEFAULT NULL COMMENT 'Comments on leave request' AFTER `type`;
    END IF;
END$$
DELIMITER ;
CALL sp_add_comments_leave_history();
DROP PROCEDURE sp_add_comments_leave_history;

-- New Leave request statuses
INSERT IGNORE INTO `status` SET `id` = 5, `name` = 'Cancellation';
INSERT IGNORE INTO `status` SET `id` = 6, `name` = 'Canceled';
