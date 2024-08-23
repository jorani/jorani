-- ---------------------------------------------------
-- This utility helps you to anonymize the data of your Jorani database
-- DO NOT execute this script on your production database as changes are
-- not reversible.
-- 
-- @license    http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- Behaviour:
-- Generate a new firstname and lastname
-- Set all e-mail addresses to benjamin.balet@gmail.com
-- Clear LDAP Path because it can contains the fullname of users
-- Change login field to login_{id}
-- Add a super admin with jorani/bbalet as credentials
-- Truncate all technical tables

UPDATE `users` SET 
`firstname` = concat(
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@lid)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('abcdefghijklmnopqrstuvwxyz', rand(@seed)*26+1, 1)
                ),
`lastname` = concat(
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@lid)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed:=round(rand(@seed)*4294967296))*26+1, 1),
                substring('ABCDEFGHIJKLMNOPQRSTUVWXYZ', rand(@seed)*26+1, 1)
                ),
`login` = CONCAT('login_', `id`),
`email` = 'benjamin.balet@gmail.com',
`ldap_path` = '';

-- Set all passwords to bbalet
UPDATE `users` 
SET `password` = '$2a$08$LeUbaGFqJjLSAN7to9URsuHB41zcmsMBgBhpZuFp2y2OTxtVcMQ.C';

-- Add a super admin
INSERT IGNORE INTO `users` (`id`, `firstname`, `lastname`, `login`, `email`, `password`, `role`, `manager`, `country`, `organization`, `contract`, `position`, `datehired`, `identifier`, `language`) VALUES
(99999, 'Admin', 'ADMIN', 'jorani', 'benjamin.balet@gmail.com', '$2a$08$LeUbaGFqJjLSAN7to9URsuHB41zcmsMBgBhpZuFp2y2OTxtVcMQ.C', 9, 1, NULL, 0, NULL, 1, NULL, NULL, 'en');

-- Remove all technical tables (PHP and OAuth2 sessions and tokens) as they are of no use
TRUNCATE TABLE `ci_sessions`;
TRUNCATE TABLE `oauth_access_tokens`;
TRUNCATE TABLE `oauth_applications`;
TRUNCATE TABLE `oauth_authorization_codes`;
TRUNCATE TABLE `oauth_clients`;
TRUNCATE TABLE `oauth_jwt`;
TRUNCATE TABLE `oauth_refresh_tokens`;
TRUNCATE TABLE `oauth_scopes`;
TRUNCATE TABLE `oauth_users`;
