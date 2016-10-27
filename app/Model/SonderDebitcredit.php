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
class SonderDebitcredit extends SonderBase {

    use ModelTrait;

    const TABLE = 'sonder_debitcredit';

    /**
     * Get project summary for a list of project
     *
     * @access public
     * @param  array      $project_ids     List of project id
     * @return \PicoDb\Table
     */
    public function getQueryColumnStats($project_ids) {
        if (empty($project_ids)) {
            return $this->db->table(SonderClient::TABLE)->limit(0);
        }


        return $this->db->table(SonderClient::TABLE)
                        ->columns(self::TABLE . '.*')
                        ->in(self::TABLE . '.id', $project_ids)
                        ->callback(array($this, 'applyColumnStats'));

        //    return $this->db->table(self::TABLE)->findAll();
    }

    /**
     * Get all project ids
     *
     * @access public
     * @return array
     */
    public function getByBankId($id)
    {
        return $this->db->table(self::TABLE)->eq('bankid',$id)->findAll('id'); 
    }

    public function getAllFromBankImport()
    {
        $q = $this->db->table(self::TABLE)->eq('open',0)->findAll();

        return $q;
    }
    public function getAllFromSystemImport()
    {
        $q = $this->db->table(self::TABLE)->eq('open',1)->findAll();

        return $q;
    }

    public function getByUserAndMonth($user_id,$month)
    {
//        $this->db->getStatementHandler()->withLogging();


        $start = date('Y-m-1 H:i:s',strtotime($month));
        $end = date("Y-m-t H:i:s",strtotime($month));

        
        $periode = $this->db->table(self::TABLE)
            ->eq('user_id',$user_id)
            ->lte('entryTimestamp', $end)
            ->gte('entryTimestamp', $start)
            ->findAll();

  //             print_r($this->db->getLogMessages());

        return $periode;
    }


    public function getAllWithdrawalsByMonthAndUser($datetime,$userid)
    {
        return $this->db->table(self::TABLE)
            ->eq('user_id',$userid)
            ->gte('entryTimestamp', date('Y-m-01 H:i:s', strtotime($datetime)))
            ->lte('entryTimestamp', date('Y-m-t H:i:s', strtotime($datetime)))
            ->findAll();
    }
}
