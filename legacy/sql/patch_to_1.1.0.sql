-- ---------------------------------------------------
-- Jorani Schema upgrade to 1.1.0
--
-- @license    http://opensource.org/licenses/MIT MIT

-- The following improvements will be added in 1.1.0:
--  * Add an index on leaves_history.id
--  * Add indexes on ci_sessions.id and ci_sessions.ip_address


-- Add an index on leaves_history.id
DELIMITER $$
CREATE PROCEDURE sp_add_index_leaves_history_id()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.STATISTICS
        WHERE table_schema = DATABASE() AND table_name = 'leaves_history' AND index_name = 'id'
    ) THEN
        ALTER TABLE `leaves_history` ADD INDEX (`id`);
    END IF;
END$$
DELIMITER ;
CALL sp_add_index_leaves_history_id();
DROP PROCEDURE sp_add_index_leaves_history_id;

-- Add indexes on ci_sessions
DELIMITER $$
CREATE PROCEDURE sp_add_indexes_ci_sessions()
    SQL SECURITY INVOKER
BEGIN
    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.STATISTICS
        WHERE table_schema = DATABASE() AND table_name = 'ci_sessions' AND index_name = 'id'
    ) THEN
        ALTER TABLE `ci_sessions` ADD INDEX (`id`);
    END IF;

    IF NOT EXISTS (
        SELECT NULL
        FROM information_schema.STATISTICS
        WHERE table_schema = DATABASE() AND table_name = 'ci_sessions' AND index_name = 'ip_address'
    ) THEN
        ALTER TABLE `ci_sessions` ADD INDEX (`ip_address`);
    END IF;
END$$
DELIMITER ;
CALL sp_add_indexes_ci_sessions();
DROP PROCEDURE sp_add_indexes_ci_sessions;
