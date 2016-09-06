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
class SonderProduct extends SonderBase {

    use ModelTrait;

    const TABLE = 'sonder_product';

    public function getList($firstrowempty = false)
    {
        $collection = array();
        if ($firstrowempty) {
            $collection[] = '';
        }
        foreach ($this->db->table(self::TABLE)->findAll() as $contract) {
            $collection[$contract['id']] = $contract['title']; 
        }
        return $collection;
    }
}
