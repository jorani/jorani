-- ---------------------------------------------------
-- Jorani Schema upgrade to 0.3.0
-- 
-- @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- New features:
--      * Define a supervisor of an entity (this user will receive a copy of accepted and rejected leave requests)
--      * Implementation of an OAuth2 server for the REST API
DELIMITER $$
CREATE PROCEDURE sp_add_new_col()
    SQL SECURITY INVOKER
BEGIN
        IF NOT EXISTS (
                SELECT NULL
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name ='organization' AND column_name = 'supervisor'
        ) THEN
                ALTER TABLE `organization` ADD `supervisor` INT NULL DEFAULT NULL COMMENT 'this user will receive a copy of accepted and rejected leave requests';
        END IF;
END$$
DELIMITER ;

CALL sp_add_new_col();
DROP PROCEDURE sp_add_new_col;

CREATE TABLE oauth_clients (client_id VARCHAR(80) NOT NULL, client_secret VARCHAR(80) NOT NULL, redirect_uri VARCHAR(2000) NOT NULL, grant_types VARCHAR(80), scope VARCHAR(100), user_id VARCHAR(80), CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));
CREATE TABLE oauth_access_tokens (access_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT access_token_pk PRIMARY KEY (access_token));
CREATE TABLE oauth_authorization_codes (authorization_code VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), redirect_uri VARCHAR(2000), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));
CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(40) NOT NULL, client_id VARCHAR(80) NOT NULL, user_id VARCHAR(255), expires TIMESTAMP NOT NULL, scope VARCHAR(2000), CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));
CREATE TABLE oauth_users (username VARCHAR(255) NOT NULL, password VARCHAR(2000), first_name VARCHAR(255), last_name VARCHAR(255), CONSTRAINT username_pk PRIMARY KEY (username));
CREATE TABLE oauth_scopes (scope TEXT, is_default BOOLEAN);
CREATE TABLE oauth_jwt (client_id VARCHAR(80) NOT NULL, subject VARCHAR(80), public_key VARCHAR(2000), CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));
