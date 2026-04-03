<?php
/**
 * This Model contains all the business logic and the persistence layer for the organization tree.
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.1.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This class allows to manage the organization of of users. Users can be attached to a node of a tree.
 * These nodes are called 'entities' and can be 'departments' or 'sub-departments', 'groups', etc.
 * It allows to use filters on a part of your structure, whatever your organization is.
 * @property CI_DB_query_builder $db
 */
class Organization_model extends CI_Model
{
    /**
     * Default constructor
     */
    public function __construct()
    {

    }

    /**
     * Get the department details of an employee (label and ID)
     * @param int $employeeId User identifier
     * @return object department details
     */
    public function getDepartment(int $employeeId): object
    {
        $this->db->select('organization.*');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->where('users.id', $employeeId);
        $query = $this->db->get();
        return $query->result()[0];
    }

    /**
     * Get the label of a given entity id
     * @param int $entityId Identifier of the entity
     * @return string name of the entity
     */
    public function getName(int $entityId): string
    {
        $this->db->from('organization');
        $this->db->where("id", $entityId);
        $query = $this->db->get();
        $record = $query->result_array();
        if (count($record) > 0) {
            return $record[0]['name'];
        } else {
            return '';
        }
    }

    /**
     * List all entities of the organisation
     * @return array<string, mixed> all entities of the organization sorted out by id and name
     */
    public function getAllEntities(): array
    {
        $this->db->from('organization');
        $this->db->order_by("parent_id", "desc");
        $this->db->order_by("name", "asc");
        return $this->db->get()->result();
    }

    /**
     * Get all children of an entity
     * @param int $entityId identifier of the entity
     * @return array<int> list of entity identifiers
     */
    public function getAllChildren(int $entityId): array
    {
        $query = "WITH RECURSIVE FamilyTree AS (
                    SELECT id, parent_id
                    FROM organization
                    WHERE parent_id = ?
                    UNION ALL
                    SELECT o.id, o.parent_id
                    FROM organization o
                    INNER JOIN FamilyTree ft ON o.parent_id = ft.id
                )
                SELECT id FROM FamilyTree";
        $query = $this->db->query($query, [$entityId]);
        if (!$query) {
            return [];
        }
        return array_map(
            'intval',
            array_column($query->result_array(), 'id')
        );
    }

    /**
     * Move an entity into the organization
     * @param int $entityId identifier of the entity
     * @param int $parentEntityId new parent id of the entity
     * @return bool result of the update query
     */
    public function move(int $entityId, int $parentEntityId): bool
    {
        $this->db->where('id', $entityId);
        return $this->db->update('organization', ['parent_id' => $parentEntityId]);
    }

    /**
     * Add an employee into an entity of the organization
     * @param int $employeeId identifier of the employee
     * @param int $entityId identifier of the entity
     * @return bool result of the update query
     */
    public function attachEmployee(int $employeeId, int $entityId): bool
    {
        $this->db->where('id', $employeeId);
        return $this->db->update('users', ['organization' => $entityId]);
    }

    /**
     * Cascade delete children and set employees' org to null
     * @param int $entityId identifier of the entity
     * @return bool result of the update and delete queries
     */
    public function delete(int $entityId): bool
    {
        if ($entityId == 0) {
            return false;   // Prevent deletion of the root entity
        }
        //Detach all employees
        $ids = $this->getAllChildren($entityId);
        array_push($ids, $entityId);
        $this->db->where_in('organization', $ids);
        $res1 = $this->db->update('users', ['organization' => null]);
        //Delete node and its children
        $this->db->where_in('id', $ids);
        $res2 = $this->db->delete('organization');
        return $res1 && $res2;
    }

    /**
     * Delete an employee from an entity of the organization
     * @param int $employeeId identifier of the employee
     * @return bool result of the query
     */
    public function detachEmployee(int $employeeId): bool
    {
        $this->db->where('id', $employeeId);
        return $this->db->update('users', ['organization' => null]);
    }

    /**
     * Rename an entity of the organization
     * @param int $entityId identifier of the entity
     * @param string $newName new name of the entity
     * @return bool result of the query
     */
    public function rename(int $entityId, string $newName): bool
    {
        $this->db->where('id', $entityId);
        return $this->db->update('organization', ['name' => $newName]);
    }

    /**
     * Create an entity in the organization
     * @param int $parentEntityId identifier of the parent entity
     * @param string $text name of the new entity
     * @return bool true if the insertion was successful, false otherwise
     */
    public function create(int $parentEntityId, string $text): bool
    {
        $data = [
            'name' => $text,
            'parent_id' => $parentEntityId
        ];
        return $this->db->insert('organization', $data);
    }

    /**
     * Copy an entity in the organization
     * @param int $sourceEntityId identifier of the source entity
     * @param int $parentEntityId identifier of the new parent entity
     * @return bool true if the copy was successful, false otherwise
     */
    public function copy(int $sourceEntityId, int $parentEntityId): bool
    {
        $this->db->from('organization');
        $this->db->where('id', $sourceEntityId);
        $query = $this->db->get();
        $row = $query->row();
        $data = [
            'name' => $row->name,
            'parent_id' => $parentEntityId
        ];
        return $this->db->insert('organization', $data);
    }

    /**
     * Returns the list of the employees attached to an entity
     * @param int $entityId identifier of the entity
     * @return array<string, mixed> Result of the query
     */
    public function employees(int $entityId): array
    {
        $this->db->select('id, firstname, lastname, email, datehired');
        $this->db->from('users');
        $this->db->where('organization', $entityId);
        $this->db->order_by('lastname', 'asc');
        $this->db->order_by('firstname', 'asc');
        return $this->db->get()->result();
    }

    /**
     * Returns the list of the employees attached to an entity
     * @param int $entityId identifier of the entity
     * @param bool $children Include sub department in the query
     * @return array<string, mixed> Result of the query
     */
    public function allEmployees(int $entityId, bool $children = false): array
    {
        $this->db->select('users.id, users.identifier, users.firstname, users.lastname, users.datehired');
        $this->db->select('organization.name as department, positions.name as position, contracts.name as contract');
        $this->db->select('contracts.id as contract_id');
        $this->db->from('organization');
        $this->db->join('users', 'users.organization = organization.id');
        $this->db->join('positions', 'positions.id  = users.position', 'left');
        $this->db->join('contracts', 'contracts.id  = users.contract', 'left');
        if ($children === true) {
            $ids = $this->getAllChildren($entityId);
            array_push($ids, $entityId);
            $this->db->where_in('organization.id', $ids);
        } else {
            $this->db->where('organization.id', $entityId);
        }
        $this->db->order_by('lastname', 'asc');
        $this->db->order_by('firstname', 'asc');
        $employees = $this->db->get()->result();
        return $employees;
    }

    /**
     * Add an employee into an entity of the organization
     * @param int $employeeId identifier of the employee
     * @param int $entityId identifier of the entity
     * @return bool result of the query
     */
    public function setSupervisor(int $employeeId, int $entityId): bool
    {
        $this->db->where('id', $entityId);
        return $this->db->update('organization', ['supervisor' => $employeeId]);
    }

    /**
     * Returns the supervisor of an entity
     * @param int $entityId identifier of the entity
     * @return ?object DB record containing user data of supervisor
     */
    public function getSupervisor(int $entityId): ?object
    {
        $this->db->select('users.id, CONCAT(users.firstname, \' \', users.lastname) as username, email', false);
        $this->db->from('organization');
        $this->db->join('users', 'users.id = organization.supervisor');
        $this->db->where('organization.id', $entityId);
        $result = $this->db->get()->result();
        if (count($result) > 0) {
            return $result[0];
        } else {
            return null;
        }
    }
}
