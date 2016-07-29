<?php

namespace Kanboard\Model;

use PicoDb\Database;

/**
 * Base model class
 *
 * @package  model
 * @author   Frederic Guillot
 */
abstract class SonderBase extends \Kanboard\Core\Base {

   
    /**
     * Save a record in the database
     *
     * @access public
     * @param  string            $table      Table name
     * @param  array             $values     Form values
     * @return boolean|integer
     */
    public function persist($table, array $values, $id = false) {
        
        if ($id) {
      
            return $this->db->transaction(function (Database $db) use ($table, $values, $id) {

                        if (!$db->table($table)->eq('id', $id)->save($values)) {
                            return false;
                        }

                        return (int) $db->getLastId();
                    });
        } else {
            return $this->db->transaction(function (Database $db) use ($table, $values) {

                        if (!$db->table($table)->save($values)) {
                            return false;
                        }

                        return (int) $db->getLastId();
                    });
        }
    }
        /**
     * Get a specific group by id
     *
     * @access public
     * @param  integer $group_id
     * @return array
     */
    public function getById($group_id) {
        return $this->getQuery()->eq('id', $group_id)->findOne();
    }

    /**
     * Get a specific group by external id
     *
     * @access public
     * @param  integer $external_id
     * @return array
     */
    public function getByExternalId($external_id) {
        return $this->getQuery()->eq('external_id', $external_id)->findOne();
    }

    /**
     * Get all groups
     *
     * @access public
     * @return array
     */
    public function getAll() {
        return $this->getQuery()->asc('name')->findAll();
    }
    
}
