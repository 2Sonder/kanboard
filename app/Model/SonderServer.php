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
class SonderServer extends SonderBase {

    
    const TABLE = 'sonder_server';
    const CREDENTIALS = 'sonder_credentials';
    const DOMAINS = 'sonder_domain';
    const CLIENTS = 'sonder_client';
    
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

        return $this->db->table(self::TABLE)->in('id', $project_ids)->asc('id')->findAll();
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
            return $this->db->table(self::TABLE)->limit(0);
        }

        //$this->db->getStatementHandler()->withLogging();
        
      //  print_r($this->db->getLogMessages());
        
//id 	servername 	sonder_credentials_id 	ipv4 	sonder_client_id 
        return $this->db->table(self::TABLE)
                        ->columns(self::TABLE . '.*')
                        ->join(self::CREDENTIALS, 'id', 'sonder_credentials_id')
                        ->in(self::TABLE . '.id', $project_ids)
                        ->callback(array($this, 'applyColumnStats'));

      //  $this->db->getStatementHandler()->withLogging();
        
    //    return $this->db->table(self::TABLE)->findAll();
    }
    
  
    public function getById($id)
    {
        return $this->db->table(self::TABLE)->eq('id',$id)->findAll();
    }
    
    public function getServersWithDomains()
    {
       // $this->db->getStatementHandler()->withLogging();
        
      //  print_r($this->db->getLogMessages());

        //Below is old code before Willem edited 12/07/2016
//        $q = $this->db->table(self::DOMAINS)
//            ->select('*,sonder_domain.id AS domainid')
//            ->left(self::TABLE, 't1', 'id', self::DOMAINS, 'sonder_server_id')
//            ->left(self::CLIENTS, 't2','id' , self::DOMAINS, 'sonder_client_id')
//            ->left(self::CREDENTIALS, 't3','sonder_entity_id' , self::DOMAINS, 'id')
//            ->findAll();

        $q = $this->db->table(self::DOMAINS)
                ->select('*,sonder_domain.id AS domainid')
                ->left(self::TABLE, 't1', 'id', self::DOMAINS, 'sonder_server_id')
                ->left(self::CLIENTS, 't2','id' , self::DOMAINS, 'sonder_client_id')
                ->findAll();
        
         return $q;
    }
    
    public function getServers()
    {
        return $this->db->table(self::TABLE)->findAll(); 
    }
    
    
    
    
    public function save($values)
    {
        
        $this->db->getStatementHandler()->withLogging();
        return $this->db->table(self::TABLE)->save($values);
        
          print_r($this->db->getLogMessages());
        
    }
    
    
    public function getQueryColumnStatsWithCredentials($project_ids) {
        
        $this->db->getStatementHandler()->withLogging();
        
        if (empty($project_ids)) {
            return $this->db->table(self::TABLE)->limit(0);
        }

        
        

        return $this->db->table(self::TABLE)
                        ->join(self::CREDENTIALS, 'server_id', 'id')
                        ->columns('.*')
                        ->in(self::TABLE . '.id', $project_ids)
                        ->callback(array($this, 'applyColumnStats'));

    //    return $this->db->table(self::TABLE)->findAll();
        
   //     print_r($this->db->getLogMessages());
    }
    
    public function getServersWithCredentials()
    {
          $this->db->getStatementHandler()->withLogging();
        
      //  print_r($this->db->getLogMessages());
        $q = $this->db->table(self::TABLE)
                ->select('*,sonder_server.id AS id,t1.user AS sshuser,t2.url AS cpurl,t2.user AS cpuser,t1.password AS sshpassword,t2.password AS cppassword')
                ->left(self::CREDENTIALS, 't1', 'id', self::TABLE, 'sonder_credentials_ssh_id')
                ->left(self::CREDENTIALS, 't2', 'id', self::TABLE, 'sonder_credentials_cp_id')
                ->left(self::CLIENTS, 't3', 'id', self::TABLE, 'sonder_client_id')
                ->findAll(); 
        
         // $this->db->getStatementHandler()->withLogging();
        
     //   print_r($this->db->getLogMessages());
        
        return $q;
    }

    

    /**
     * Get all project ids
     *
     * @access public
     * @return array
     */
    public function getAllIds() {
        return $this->db->table(self::TABLE)->asc('id')->findAllByColumn('id'); 
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
