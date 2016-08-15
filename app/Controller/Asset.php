<?php

namespace Kanboard\Controller;

/**
 * Project controller (Settings + creation/edition)
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Asset extends Base {

    /**
     * List of projects
     *
     * @access public
     */
    public function index() {
        
        
        $project_ids = $this->sonderClient->getAllIds();
        
     //   print_r($project_ids);
        
        $user = $this->userSession->getId();

        
        $c = array();
        $clienten = $this->paginator
                ->setUrl('asset', 'index')
                ->setMax(20)
                ->setOrder('name')
                ->setQuery($this->sonderClient->getQueryColumnStats($project_ids))
                ->calculate();


        foreach($clienten->getCollection() as $index => $client)
        {
            $val = $this->permissionCheck($user, $client['id']);
            $val2 = $this->permissionCheck($user, $client['parent_id']);

            if($val || $val2){
                $c[$index] = $client;
                $c[$index]['credentials'] = $this->sonderCredentials->getClientCredentialsById($client['id']);
                $c[$index]['permissions'] = $this->sonderClientUserPermissions->getUsersByClient($client['id']);
            }
        }
        
        $clienten->setCollection($c);

        $this->response->html($this->helper->layout->app('asset/layout', array(
                    'nb_projects' => 'project',
                    'title' => 'Assets / Clients',
                    'data' => array('paginator' => $clienten, 'c' => $c),
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'asset/index',
        )));
    }

    public function byclients() {
        $project_ids = $this->sonderServer->getAllIds();
        $paginator = $this->paginator
                ->setUrl('asset', 'byclients')
                ->setMax(20)
                ->setOrder('id')
                ->setQuery($this->sonderServer->getQueryColumnStats($project_ids))
                ->calculate();

        $this->response->html($this->helper->layout->app('asset/layout', array(
                    'data' =>
                    array('paginator' => $paginator),
                    'nb_projects' => 'project',
                    'title' => 'Assets by clients',
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'asset/byclients'
        )));
    }

    public function server() {
        $user = $this->userSession->getId();
        $servers = array();
        foreach($this->sonderServer->getServersWithCredentials() as $server)
        {

            if($this->permissionCheck($user, $server['parent_id']) || $this->permissionCheck($user, $server['sonder_client_id'])) {
                $s = $server;

                $s['credentials'] = $this->sonderCredentials->getServerCredentialsById($server['id']);

                $servers[] = $s;
            }
        }

        $this->response->html($this->helper->layout->app('asset/layout', array(
                    'data' =>
                    array('servers' => $servers,
                        'clients' => $this->sonderClient->getAll()
                    ),
                    'nb_projects' => 'project',
                    'title' => 'Assets / Servers',
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'asset/server'
        )));
    }

    public function addserver() {
        $values = $this->request->getValues();
        $errors = array();

        $cp = array('url' => 'url', 'user' => $values['cpuser'], 'password' => $values['cppassword']);
        $this->sonderCredentials->save($cp);
        $cpid = $this->sonderCredentials->getLastId();

        $ssh = array('url' => 'url', 'user' => $values['sshuser'], 'password' => $values['sshpassword']);
        $this->sonderCredentials->save($ssh);
        $sshid = $this->sonderCredentials->getLastId();

        $server = array('servername' => $values['servername'], 'ipv4' => $values['ipv4'], 'sonder_credentials_ssh_id' => $sshid, 'sonder_credentials_cp_id' => $cpid, 'sonder_client_id' => $values['sonder_client_id']);
        $this->sonderServer->save($server);

        $this->response->redirect($this->helper->url->to('asset', 'server', array()));
    }

    public function byservers() {

        $user = $this->userSession->getId();
        $domains = array();

        foreach($this->sonderServer->getServersWithDomains() as $index => $domain)
        {
            if($this->permissionCheck($user, $domain['parent_id']) || $this->permissionCheck($user, $domain['id'])) {
                $domain['credentials'] = $this->sonderCredentials->getDomainCredentialsById($domain['domainid']);
                $domains[] = $domain;
            }
        }


        $this->response->html($this->helper->layout->app('asset/layout', array(
            'data' =>
            array('servers' => $this->sonderServer->getServers(),
                'paginator' => $domains,
                'clients' => $this->sonderClient->getAll()
            ),
            'nb_projects' => 'project',
            'title' => 'Assets / Domains',
            'sidebar_template' => 'asset/sidebar',
            'sub_template' => 'asset/byservers'
        )));
    }

    public function newasset() {
        $this->response->html($this->helper->layout->app('asset/layout', array(
                    'paginator' => 'page',
                    'nb_projects' => 'project',
                    'title' => 'Clients',
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'asset/new',
                    'asset_id' => 'asset_id'
        )));
    }

    public function adddomain() {
        $values = $this->request->getValues();

        $this->sonderDomain->save($values);

        $this->response->redirect($this->helper->url->to('asset', 'byservers', array()));
    }

    public function addclient() {
        $values = $this->request->getValues();

        $this->sonderClient->save($values);

        $this->response->redirect($this->helper->url->to('asset', 'index', array()));
    }
    
    public function editdomain()
    {
        $id = $_GET['id'];
        $this->response->html($this->helper->layout->app('asset/layout', array(
                    'data' =>
                    array( 
                        'servers' => $this->sonderServer->getServers(),
                        'paginator' => $this->sonderServer->getServersWithDomains(),
                        'clients' => $this->sonderClient->getAll(),
                        'credentials' => $this->sonderCredentials->getAllByEntity($id,'sonder_domain'),
                        'domainid' => $id,
                        'domain' => $this->sonderDomain->getById($id)
                    ),
                    'nb_projects' => 'project',
                    'title' => 'Assets by servers',
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'asset/editdomain'
        )));
    }
    
    public function editserver()
    {
        $id = $_GET['id'];
        $this->response->html($this->helper->layout->app('asset/layout', array(
            'data' =>
            array(
                'server' => $this->sonderServer->getById($id),
                'credentials' => $this->sonderCredentials->getAllByEntity($id,'sonder_server'),
                'clients' => $this->sonderClient->getAll()
            ),
            'nb_projects' => 'project',
            'title' => 'Assets by servers',
            'sidebar_template' => 'asset/sidebar',
            'sub_template' => 'asset/editserver'
        )));
    }

    public function saveeditserver()
    {
        $id = $_GET['id'];
        $value = $this->request->getValues();
        $value['id'] = $id;

        $this->sonderServer->update($value);
        $this->response->redirect($this->helper->url->to('asset', 'editdomain', array('id' => $id)));
    }
    
    public function saveeditdomain()
    {

        $id = $_GET['id'];
        $value = $this->request->getValues();
        $value['id'] = $id;

        $this->sonderDomain->update($value);

        $this->response->redirect($this->helper->url->to('asset', 'editdomain', array('id' => $id)));
    }

    public function removeDomain()
    {
        $id = $_GET['id'];
        $this->sonderDomain->remove($id);

        $this->response->redirect($this->helper->url->to('asset', 'byservers'));
    }

    public function removeServer()
    {
        $id = $_GET['id'];
        $this->sonderServer->remove($id);

        $this->response->redirect($this->helper->url->to('asset', 'server'));
    }

    public function removeClient()
    {
        $id = $_GET['id'];
        $this->sonderClient->remove($id);

        $this->response->redirect($this->helper->url->to('asset', 'index'));
    }


    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save() {
        $values = $this->request->getValues();
        $errors = array();

        $this->sonderCredentials->save($values);
        $this->sonderServer->save($values);

        $values['id'] = 3;
        $this->sonderClient->create($values);

        $this->response->redirect($this->helper->url->to('asset', 'index', array()));
    }

    private function permissionCheck($userId, $clientId){
        if($this->sonderClientUserPermissions->existsClientUser($clientId,$userId) == "true" || $this->userSession->isAdmin()){
            return true;
        }else{
            return false;
        }
    }

    public function search($term){
//        $clienten = $this->paginator
//            ->setUrl('asset', 'index')
//            ->setMax(20)
//            ->setOrder('name')
//            ->setQuery($this->sonderClient->getQueryColumnStats($project_ids))
//            ->calculate();
    }

}
