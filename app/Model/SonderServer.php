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

    use ModelTrait;
    
    const TABLE = 'sonder_server';
    const CREDENTIALS = 'sonder_credentials';
    const DOMAINS = 'sonder_domain';
    const CLIENTS = 'sonder_client';
    

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
    
    public function getServersWithDomains()
    {
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
    }
    
    public function getServersWithCredentials()
    {
          $this->db->getStatementHandler()->withLogging();
        $q = $this->db->table(self::TABLE)
                ->select('*,sonder_server.id AS id,t1.user AS sshuser,t2.url AS cpurl,t2.user AS cpuser,t1.password AS sshpassword,t2.password AS cppassword')
                ->left(self::CREDENTIALS, 't1', 'id', self::TABLE, 'sonder_credentials_ssh_id')
                ->left(self::CREDENTIALS, 't2', 'id', self::TABLE, 'sonder_credentials_cp_id')
                ->left(self::CLIENTS, 't3', 'id', self::TABLE, 'sonder_client_id')
                ->findAll();
        return $q;
    }
}
