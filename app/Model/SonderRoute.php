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
class SonderRoute extends SonderBase
{
    use ModelTrait;

    const TABLE = 'sonder_route';


    public function getAllWithusersAndClients()
    {
        $q = $this->db->table(self::TABLE)
            ->select('*, t1.name AS clientname, t2.name AS username, sonder_route.id AS id,sonder_route.postcode AS postcode, sonder_route.adres AS adres, sonder_route.plaats AS plaats ')
            ->left('sonder_client', 't1', 'id', self::TABLE, 'sonder_client_id')
            ->left('users', 't2', 'id', self::TABLE, 'user_id')
            ->findAll();

        return $q;
    }
}
