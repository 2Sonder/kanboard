<?php

namespace Kanboard\Controller;

/**
 * Project controller (Settings + creation/edition)
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Credentials extends Base {

    /**
     * List of projects
     *
     * @access public
     */
    public function save() {
        $values = $this->request->getValues();

        print_r($values);
    
        $index = $values['index'];
        if (isset($values['index'])) {
            $index = ($values['index'] + 1);
            for ($i = 0; $i < $index; $i++) {
                $vals['id'] = $values['id_' . $i];
                unset($values['id_' . $i]);
                $vals['type'] = $values['type_' . $i];
                unset($values['type_' . $i]);
                $vals['url'] = $values['url_' . $i];
                unset($values['url_' . $i]);
                $vals['user'] = $values['user_' . $i];
                unset($values['user_' . $i]);
                $vals['password'] = $values['password_' . $i];
                unset($values['password_' . $i]);
                $vals['sonder_entity_id'] = $values['sonder_entity_id'];
                unset($values['sonder_entity_name']);
                $vals['sonder_entity_name'] = $values['sonder_entity_name'];

                $emptyCheck = array($this->isempty($vals['type']), $this->isempty($vals['url']), $this->isempty($vals['user']), $this->isempty($vals['password']));
                if (in_array(false, $emptyCheck))
                {
                    //At least one value is filled so we update it.
                    $this->sonderCredentials->update($vals);
                }else{
                    //uncomment if you need to remove empty lines
                   //$this->sonderCredentials->remove($vals['id']);
                }
            }
        }
        unset($values['index']);

        $emptyCheck = array($this->isempty($values['type']), $this->isempty($values['url']), $this->isempty($values['user']), $this->isempty($values['password']));
        if (in_array(false, $emptyCheck))
        {
            //At least one value is filled so we save it.
        //    $values['sonder_entity_name'] = 'sonder_domain';
            $this->sonderCredentials->save($values);
        }

        $valId = $values['sonder_entity_id'];
        unset($values['sonder_entity_id']);
        
        switch($values['sonder_entity_name'])
        {
            case 'sonder_client':
                $this->response->redirect($this->helper->url->to('client', 'newclient', array('client_id' => $valId)));
                break;
            case 'sonder_domain':
                $this->response->redirect($this->helper->url->to('asset', 'edit', array('id' => $valId)));
                break;
            case 'sonder_server':
                $this->response->redirect($this->helper->url->to('asset', 'editserver', array('id' => $valId)));
                break;
        }
        
        
        
        unset($values['sonder_entity_name']);
    }

    private function isempty($val){
        if($val == '' || $val == ' ' || $val == null){
            return true;
        }else{
            return false;
        }
    }

    public function remove(){
        $values = $this->request->getValues();
        $id = $_GET["id"];

        $this->sonderCredentials->remove($id);

        $valId = $values['sonder_entity_id'];
        unset($values['sonder_entity_id']);

        $this->response->redirect($this->helper->url->to('asset', 'editdomain', array('id' => $valId)));
    }

}
