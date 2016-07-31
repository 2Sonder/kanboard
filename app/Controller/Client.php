<?php

namespace Kanboard\Controller;

/**
 * Project controller (Settings + creation/edition)
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Client extends Base {

    /**
     * List of projects
     *
     * @access public
     */
    public function index() {
        /*
          if ($this->userSession->isAdmin()) {

          } else {
          $project_ids = $this->projectPermission->getActiveProjectIds($this->userSession->getId());
          }

          $nb_projects = count($project_ids);
         */

        //$project_ids = [];

        $project_ids = $this->sonderClient->getAllIds();


        $paginator = $this->paginator
                ->setUrl('client', 'index')
                ->setMax(20)
                ->setOrder('name')
                ->setQuery($this->sonderClient->getQueryColumnStats($project_ids))
                ->calculate();

        $first = $this->sonderClient->getAll();

        //array('paginator' => $paginator,'nb_projects' => $nb_projects,'title' => $title


        $this->response->html($this->helper->layout->app('client/layout', array(
                    'data' => array(
                        'paginator' => $paginator,
                        'nb_projects' => 'project'
                    ),
                    'sidebar_template' => 'client/sidebar',
                    'sub_template' => 'client/index',
                    'title' => 'Assets / Clients'
        )));
    }

    public function newclient() {

        if (isset($_GET['client_id'])) {
            $id = $_GET['client_id'];
        } else {
            $id = 0;
        }

        $client = $this->sonderClient->getById($id);
        $this->response->html($this->helper->layout->app('client/layout', array(
                    'data' => array(
                        'paginator' => 'page',
                        'nb_projects' => 'project',
                        'title' => 'Clients',
                        'errors' => array(),
                        'clients' => $this->sonderClient->getAll(),
                        'client' => $client,
                        'permissions' => $this->sonderClientUserPermissions->getUsersByClient($id),
                        'credentials' => $this->sonderCredentials->getAllByEntity($id, 'sonder_client'),
                        'users' => $this->user->getAll()
                    ),
                    'sidebar_template' => 'asset/sidebar',
                    'sub_template' => 'client/new',
                    'client_id' => 'client_id',
                    'title' => 'Assets / New client'
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save() {
        $values = $this->request->getValues();
        $errors = array();
        $id = $_GET['client_id'];
        $permissions = $values['permission'];
        unset($values['permission']);

        if (strlen($values['parent_id']) == 0) {
            $values['parent_id'] = 0;
        }

        $this->sonderClient->save($values);

        if (!empty($_GET['client_id'])) {
            $id = $_GET['client_id'];
        }else{
            $id = $this->db->getLastId();
        }

        foreach ($permissions as $per){
            $this->sonderClientUserPermissions->addClientUserPermission($id, $per);
        }

//        print_r($values);
//        print_r($id);
//        print_r($permissions);
//        print_r( $this->sonderClientUserPermissions->getClientUserPermissions());


        $this->response->redirect($this->helper->url->to('asset', 'index', array()));
    }

    /**
     * Show the project information page
     *
     * @access public
     */
    public function show() {

        echo 'show';
        /*
          $project = $this->getProject();

          $this->response->html($this->helper->layout->project('project/show', array(
          'project' => $project,
          'stats' => $this->project->getTaskStats($project['id']),
          'title' => $project['name'],
          ))); */
    }

    /**
     * Remove a project
     *
     * @access public
     */
    public function remove() {
        /*
          $project = $this->getProject();

          if ($this->request->getStringParam('remove') === 'yes') {
          $this->checkCSRFParam();

          if ($this->project->remove($project['id'])) {
          $this->flash->success(t('Project removed successfully.'));
          } else {
          $this->flash->failure(t('Unable to remove this project.'));
          }

          $this->response->redirect($this->helper->url->to('project', 'index'));
          }

          $this->response->html($this->helper->layout->project('project/remove', array(
          'project' => $project,
          'title' => t('Remove project')
          )));
         * 
         */
    }

}
