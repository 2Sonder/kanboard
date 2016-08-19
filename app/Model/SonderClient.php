<?php

namespace Kanboard\Model;

use Kanboard\Core\Security\Token;
use Kanboard\Core\Security\Role;
use Kanboard\Model\Base;

/**
 * Project model
 *
 * @package  model
 * @author   Frederic Guillot
 */
class SonderClient extends SonderBase
{

    const TABLE = 'sonder_client';

    /**
     * Get query to fetch all groups
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function getQuery()
    {
        return $this->db->table(self::TABLE);
    }

    /**
     * Search groups by name
     *
     * @access public
     * @param  string $input
     * @return array
     */
    public function search($input)
    {
        return $this->db->table(self::TABLE)->ilike('name', '%' . $input . '%')->asc('name')->findAll();
    }

    /**
     * Remove a group
     *
     * @access public
     * @param  integer $group_id
     * @return array
     */
    public function remove($group_id)
    {
        return $this->db->table(self::TABLE)->eq('id', $group_id)->remove();
    }

    /**
     * Get all projects with given Ids
     *
     * @access public
     * @param  integer[] $project_ids
     * @return array
     */
    public function getAllByIds(array $project_ids)
    {
        if (empty($project_ids)) {
            return array();
        }

        return $this->db->table(self::TABLE)->in('id', $project_ids)->asc('name')->findAll();
    }

    /**
     * Get project summary for a list of project
     *
     * @access public
     * @param  array $project_ids List of project id
     * @return \PicoDb\Table
     */
    public function getQueryColumnStats($project_ids)
    {
        if (empty($project_ids)) {
            return $this->db->table(SonderClient::TABLE)->limit(0);
        }

        return $this->db->table(SonderClient::TABLE)
            ->columns(self::TABLE . '.*')
            ->in(self::TABLE . '.id', $project_ids)
            ->callback(array($this, 'applyColumnStats'));
    }

    /**
     * Get all project ids
     *
     * @access public
     * @return array
     */
    public function getAllIds()
    {
        return $this->db->table(self::TABLE)->asc('name')->findAllByColumn('id');
    }

    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
    }

    public function getList()
    {
        $clients = array();
        foreach ($this->db->table(self::TABLE)->findAll() as $client) {
            $clients[$client['id']] = $client['name'];
        }
        return $clients;
    }

    public function getHighestId()
    {
        return $this->db->table(self::TABLE)->desc('id')->findOne();
    }

    public function save($values)
    {
        if (isset($values['id'])) {
            return $this->db->table(self::TABLE)->eq('id',$values['id'])->save($values);
        } else {
            return $this->db->table(self::TABLE)->save($values);
        }
    }

    /**
     * Update existing group
     *
     * @access public
     * @param  array $values
     * @return boolean
     */
    public function update(array $values)
    {
        return $this->db->table(self::TABLE)->eq('id', $values['id'])->update($values);
    }

    public function getById($id)
    {
        return $this->db->table(self::TABLE)->eq('id', $id)->findOne();
    }

    /**
     * Create a new group
     *
     * @access public
     * @param  string $name
     * @param  string $external_id
     * @return integer|boolean
     */
    public function create($values)
    {
        if (is_array($values)) {
            return $this->persist(self::TABLE, $values, $values['id']);
        }
    }

}
