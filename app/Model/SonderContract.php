<?php

namespace Kanboard\Model;

use Kanboard\Core\Security\Token;
use Kanboard\Core\Security\Role;
use Kanboard\Model\Base;
use Kanboard\Model\ModelTrait;

/**
 * Project model
 *
 * @package  model
 * @author   Frederic Guillot
 */
class SonderContract extends SonderBase
{
    use ModelTrait;

    const TABLE = 'sonder_contract';

    public function getList($firstrowempty = false, $sonder_client_id = false)
    {
        $collection = array();
        if ($firstrowempty) {
            $collection[] = '';
        }
        foreach ($this->db->table(self::TABLE)->eq('sonder_client_id', $sonder_client_id)->findAll() as $contract) {
            $collection[$contract['id']] = $contract['name'];
        }
        return $collection;
    }

    public function getAll()
    {
        $q = $this->db->table(self::TABLE)
            ->select('*,t1.name AS clientname,t2.title AS productname , sonder_contract.name AS name,sonder_contract.id AS id,sonder_contract.description AS description ')
            ->left('sonder_client', 't1', 'id', self::TABLE, 'sonder_client_id')
            ->left('sonder_product', 't2', 'id', self::TABLE, 'sonder_product_id')
            ->findAll();
        return $q;
    }


    public function getPeriodByClient($start, $end, $client_id)
    {
        $periode = $this->db->table(self::TABLE)
            ->lte('creation_date', $end)
            ->gte('creation_date', $start)
            ->eq('sonder_client_id', $client_id)
            ->findAll();
        return $periode;
    }

    public function getByPeriodAndClient($start, $end, $client)
    {
        return $this->db->table(self::TABLE)
            ->lte('date', $end)
            ->gte('dateto', $start)
            ->eq('sonder_client_id', $client)
            ->findAll();
    }
}
