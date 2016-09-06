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
class SonderInvoice extends SonderBase
{
    use ModelTrait;

    const TABLE = 'sonder_invoice';

    public function getNextInvoiceNumber()
    {
        $invoice = $this->db->table(self::TABLE)->desc('number')->findOne();
        if ($invoice) {
            $number = explode('SO', $invoice['number']);
            return intval($number[1] + 1);
        } else {
            return false;
        }
    }

    public function getByPeriodAndClient($start, $end, $client)
    {
        return $this->db->table(self::TABLE)
            ->lte('date', $end)
            ->gte('dateto', $start)
            ->eq('sonder_client_id', $client)
            ->findAll();
    }

    public function getAllWithClients()
    {
        return $this->db->table(self::TABLE)
            ->select('*,sonder_invoice.number AS invoicenumber, sonder_invoice.id AS invoiceid, sonder_invoice.id AS id')
            ->left('sonder_client', 't1', 'id', self::TABLE, 'sonder_client_id')
            ->findAll();
    }
}
