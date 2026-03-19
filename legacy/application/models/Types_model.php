<?php
/**
 * This file contains all the business logic and the persistence layer for the types of leave request.
 * 
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.4.0
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * This Class contains all the business logic and the persistence layer for the types of leave request.
 */
class Types_model extends CI_Model
{

    /**
     * Default constructor
     * 
     */
    public function __construct()
    {

    }

    /**
     * Get the list of types or one type
     * @param int $id optional id of a type
     * @return array<string, mixed>|null list of type(s) or NULL if list was not found
     */
    public function getTypes(int $id = 0): ?array
    {
        if ($id === 0) {
            $query = $this->db->get('types');
            return $query->result_array();
        }
        $this->db->where('id', $id);
        $query = $this->db->get('types');
        return $query->row_array();
    }

    /**
     * Get the list of types or one type
     * @param string $name type name
     * @return array<string, mixed>|null record of a leave type
     */
    public function getTypeByName(string $name): ?array
    {
        $this->db->where('name', $name);
        $query = $this->db->get('types');
        return $query->row_array();
    }

    /**
     * Get the list of types as an ordered associative array
     * @return array<int, string> Associative array of types (id, name)
     */
    public function getTypesAsArray(int $id = 0): array
    {
        $listOfTypes = [];
        $this->db->from('types');
        $this->db->order_by('name');
        $rows = $this->db->get()->result_array();
        foreach ($rows as $row) {
            $listOfTypes[(int) $row['id']] = $row['name'];
        }
        return $listOfTypes;
    }

    /**
     * Get the name of a given type id
     * @param int $id ID of the type
     * @return string label of the type
     */
    public function getName(int $id): string
    {
        $type = $this->getTypes($id);
        return $type && array_key_exists('name', $type) ? $type['name'] : '';
    }

    /**
     * Insert a new leave type. Data are taken from HTML form.
     * @param string $name name of the type
     * @param bool $deduct Deduct days off
     * @param string $acronym Acronym of leave type
     */
    public function setTypes(string $name, bool $deduct, string $acronym): void
    {
        $data = [
            'acronym' => $acronym,
            'name' => $name,
            'deduct_days_off' => $deduct
        ];
        $this->db->insert('types', $data);
    }

    /**
     * Delete a leave type from the database
     * @param int $id identifier of the leave type
     */
    public function deleteType(int $id): void
    {
        $this->db->delete('types', ['id' => $id]);
    }

    /**
     * Update a given leave type in the database.
     * @param int $id identifier of the leave type
     * @param string $name name of the type
     * @param bool $deduct Deduct days off
     * @param string $acronym Acronym of leave type
     * @return bool TRUE if the SQL query is successful, FALSE otherwise
     */
    public function updateTypes(int $id, string $name, bool $deduct, string $acronym): bool
    {
        $data = [
            'acronym' => $acronym,
            'name' => $name,
            'deduct_days_off' => $deduct
        ];
        $this->db->where('id', $id);
        return $this->db->update('types', $data);
    }

    /**
     * Count the number of time a leave type is used into the database
     * @param int $id identifier of the leave type record
     * @return int number of times the leave type is used
     */
    public function usage(int $id): int
    {
        $this->db->select('COUNT(*)');
        $this->db->from('leaves');
        $this->db->where('type', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['COUNT(*)'];
    }
}
