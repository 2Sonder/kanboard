<?php

namespace Kanboard\Model;

use PicoDb\Database;

/**
 * Base model class
 *
 * @package  model
 * @author   Frederic Guillot
 */
abstract class Base extends \Kanboard\Core\Base {

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
}
