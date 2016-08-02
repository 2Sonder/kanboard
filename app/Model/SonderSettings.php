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
class SonderSettings extends SonderBase {

    const TABLE = 'sonder_settings';

    /**
     * Get query to fetch all groups
     *
     * @access public
     * @return \PicoDb\Table
     */
    public function getQuery() {
        return $this->db->table(self::TABLE);
    }

    /**
     * Search groups by name
     *
     * @access public
     * @param  string  $input
     * @return array
     */
    public function search($input) {
        return $this->db->table(self::TABLE)->ilike('name', '%' . $input . '%')->asc('name')->findAll();
    }

    /**
     * Remove a group
     *
     * @access public
     * @param  integer $group_id
     * @return array
     */
    public function remove($group_id) {
        return $this->db->table(self::TABLE)->eq('id', $group_id)->remove();
    }

    public function save($values) {

        $this->db->getStatementHandler()->withLogging();
        
        print_r($this->db->getLogMessages());
        
        return $this->db->table(self::TABLE)->save($values);

        $this->db->getStatementHandler()->withLogging();
        
        print_r($this->db->getLogMessages());
    }
    
    

    /**
     * Update existing group
     *
     * @access public
     * @param  array $values
     * @return boolean
     */
    public function update(array $values) {
        return $this->db->table(self::TABLE)->eq('id', $values['id'])->update($values);
    }

    /**
     * Get all projects with given Ids
     *
     * @access public
     * @param  integer[]   $project_ids
     * @return array
     */
    public function getAllByIds(array $project_ids) {
        if (empty($project_ids)) {
            return array();
        }

        return $this->db->table(self::TABLE)->in('id', $project_ids)->asc('name')->findAll();
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
     * Get all project ids
     *
     * @access public
     * @return array
     */
    public function getAllIds() {
        return $this->db->table(self::TABLE)->asc('name')->findAllByColumn('id');
    }

    public function getById($id) {
        return $this->db->table(self::TABLE)->eq('id', $id)->findAll();
    }
    
    public function getLastId()
    {
        $r = $this->db->table(self::TABLE)->desc('id')->findAllByColumn('id');
        return $r[0];
    }
    
    public function getAll() {
        return $this->db->table(self::TABLE)->findAll();
    }
    
    public function getAllByKey()
    {
        $settings = array();
        foreach ($this->getAll() as $setting) {
            $settings[$setting['settingkey']] = $setting;
        }
        return $settings;
    }
    
    
    public function getAllWithClients() {
        
        //$this->db->getStatementHandler()->withLogging();
        
       // print_r($this->db->getLogMessages());
        
        /*
         $q = $this->db->table(self::DOMAINS)
                ->select('*,sonder_domain.id AS domainid')
                ->left(self::TABLE, 't1', 'id', self::DOMAINS, 'sonder_server_id')
                ->left(self::CLIENTS, 't2','id' , self::DOMAINS, 'sonder_client_id')
                ->findAll();
        */
        //->select('*,sonder_domain.id AS domainid')
                                          //->left('sonder_client', 't1', 'sonder_client_id', self::TABLE, 'id')
        return $this->db->table(self::TABLE)
                ->select('*,sonder_invoice.number AS invoicenumber')
                ->left('sonder_client', 't1', 'id', self::TABLE, 'sonder_client_id')
                ->findAll();
        
     //    print_r($this->db->getLogMessages());
    }

    /**
     * Create a new group
     *
     * @access public
     * @param  string  $name
     * @param  string  $external_id
     * @return integer|boolean
     */
    public function create($values) {
        if (is_array($values)) {
            return $this->persist(self::TABLE, $values, $values['id']);
        }
    }

}