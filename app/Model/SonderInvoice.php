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
      //  $this->db->getStatementHandler()->withLogging();

        $q = $this->db->table(self::TABLE)
            ->select('*, sonder_invoice.number AS invoicenumber, sonder_invoice.id AS id, t2.id AS contractid, t2.name AS contractname, t1.name AS clientname ')
            ->left('sonder_client', 't1', 'id', self::TABLE, 'sonder_client_id')
            ->left('sonder_contract', 't2', 'id', self::TABLE, 'sonder_contract_id')
            ->findAll();

        //print_r($this->db->getLogMessages());

        return $q;
    }

    public function getAllContractIds()
    {
        $ids = [];
        foreach($this->db->table(self::TABLE)->asc('sonder_contract_id')->findAllByColumn('sonder_contract_id') as $contract)
        {
            if($contract > 0)
            {
                $ids[] = $contract;
            }
        }
        return $ids;
    }

    public function getAllMonthlyInvoices()
    {

        $this->db->getStatementHandler()->withLogging();

        $invoices = [];
        foreach($this->db->table(self::TABLE)->findAll() as $invoice)
        {
        //    echo $invoice['id'];
            if($invoice['sonder_contract_id'] > 0)
            {

            }
            else
            {
                $invoices[] = $invoice;
            }
        }

   //         print_r($this->db->getLogMessages());

        return $invoices;
    }
}
