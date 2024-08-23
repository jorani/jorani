-- ---------------------------------------------------
-- Jorani Schema upgrade to 0.4.0
-- 
-- @license      http://opensource.org/licenses/AGPL-3.0 AGPL-3.0
-- @copyright  Copyright (c) 2014-2023 Benjamin BALET

-- New features:
--      * Define delegates for a manager (employees who can accept/reject requests in behalf of a manager)

CREATE TABLE IF NOT EXISTS `delegations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Id of delegation',
  `manager_id` int(11) NOT NULL COMMENT 'Manager wanting to delegate',
  `delegate_id` int(11) NOT NULL COMMENT 'Employee having the delegation',
  PRIMARY KEY (`id`),
  KEY `manager_id` (`manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Delegation of approval' AUTO_INCREMENT=1 ;

-- History tables feature is given up into v0.40
DROP TABLE IF EXISTS `activities_employee_history`;
DROP TABLE IF EXISTS `activities_history`;
DROP TABLE IF EXISTS `contracts_history`;
DROP TABLE IF EXISTS `entitleddays_history`;
DROP TABLE IF EXISTS `leaves_history`;
DROP TABLE IF EXISTS `organization_history`;
DROP TABLE IF EXISTS `overtime_history`;
DROP TABLE IF EXISTS `positions_history`;
DROP TABLE IF EXISTS `types_history`;
DROP TABLE IF EXISTS `users_history`;
