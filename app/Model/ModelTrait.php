<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/5/2016
 * Time: 9:18 PM
 */

namespace Kanboard\Model;


trait ModelTrait
{



    public function getAllByKey()
    {
        $settings = array();
        foreach ($this->getAll() as $setting) {
            $settings[$setting['settingkey']] = $setting;
        }
        return $settings;
    }


    /**
     * Get project summary for a list of project
     *
     * @access public
     * @param  array      $project_ids     List of project id
     * @return \PicoDb\Table
     */
    public function getQueryColumnStats($project_ids) {
        if (empty($project_ids)) {
            return $this->db->table(SonderClient::TABLE)->limit(0);
        }


        return $this->db->table(SonderClient::TABLE)
            ->columns(self::TABLE . '.*')
            ->in(self::TABLE . '.id', $project_ids)
            ->callback(array($this, 'applyColumnStats'));

        //    return $this->db->table(self::TABLE)->findAll();
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

    public function save($values)
    {
        $this->db->getStatementHandler()->withLogging();

        if (isset($values['id'])) {
            $q = $this->db->table(self::TABLE)->eq('id', $values['id'])->update($values);
        } else {
            $q = $this->db->table(self::TABLE)->save($values);
        }

        print_r($this->db->getLogMessages());

        return $q;
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
     * Get query to fetch all groups
     *
     * @access public
     * @return \PicoDb\Table
     */

    public function getQuery()
    {
        return $this->db->table(self::TABLE);
    }

    public function getAllIds()
    {
        return $this->db->table(self::TABLE)->asc('name')->findAllByColumn('id');
    }

    public function getById($id)
    {
        return $this->db->table(self::TABLE)->eq('id', $id)->findOne();
    }

    public function getLastId()
    {
        $r = $this->db->table(self::TABLE)->desc('id')->findAllByColumn('id');
        return $r[0];
    }

    public function getAll()
    {
        return $this->db->table(self::TABLE)->findAll();
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

}