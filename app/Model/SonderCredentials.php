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
class SonderCredentials extends SonderBase {

    use ModelTrait;

    const TABLE = 'sonder_credentials';

    public function getAllByEntity($entity_id,$entity_name)
    {
        return $this->db->table(self::TABLE)->eq('sonder_entity_id',$entity_id)->eq('sonder_entity_name',$entity_name)->findAll();
    }

    public function getDomainCredentials()
    {
        return $this->db->table(self::TABLE)->eq('sonder_entity_name','sonder_domain')->findAll();
    }

    
    private function getByTypeAndId($type,$id)
    {
        return $this->db->table(self::TABLE)->eq('sonder_entity_id',$id)->eq('sonder_entity_name',$type)->findAll();
    }
    
    public function getClientCredentialsById($id)
    {
        return $this->getByTypeAndId('sonder_client',$id);
    }
    
    public function getServerCredentialsById($id)
    {   
       return $this->getByTypeAndId('sonder_server',$id);
    }
    
    public function getDomainCredentialsById($id)
    {
        return $this->getByTypeAndId('sonder_domain',$id);
    }
}
