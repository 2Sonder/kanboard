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
class SonderDomain extends SonderBase {

    use ModelTrait;
    
    const TABLE = 'sonder_domain';

    public function getDomainsByUser($user_id)
    {
        $domains = array();

        foreach($this->sonderServer->getServersWithDomains() as $index => $domain)
        {
            if($this->permissionCheck($user_id, $domain['parent_id']) || $this->permissionCheck($user_id, $domain['id'])) {
                $domain['credentials'] = $this->sonderCredentials->getDomainCredentialsById($domain['domainid']);
                $domains[] = $domain;
            }
        }
        return $domains;
    }

    private function permissionCheck($userId, $clientId){
        if($this->sonderClientUserPermissions->existsClientUser($clientId,$userId) == "true" || $this->userSession->isAdmin()){
            return true;
        }else{
            return false;
        }
    }

}
