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
class SonderInvoiceLine extends SonderBase {

    use ModelTrait;

    const TABLE = 'sonder_invoice_line';


    public function getByInvoiceId($id)
    {
        return $this->db->table(self::TABLE)->eq('sonder_invoice_id',$id)->findAll();
    }

    public function existBytaskId($id)
    {
        return $this->db->table(self::TABLE)->eq('task_id', $id)->findAll();
    }

    public function existByContractId($id)
    {
        return $this->db->table(self::TABLE)->eq('sonder_contract_id', $id)->findAll();
    }
}
