<?php

namespace Kanboard\Controller;

use Kanboard\Model\Project as ProjectModel;
use Kanboard\Model\Subtask as SubtaskModel;

/**
 * Application controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class App extends Base
{
    /**
     * Get project pagination
     *
     * @access private
     * @param  integer $user_id
     * @param  string $action
     * @param  integer $max
     * @return \Kanboard\Core\Paginator
     */
    private function getProjectPaginator($user_id, $action, $max)
    {
        return $this->paginator
            ->setUrl('app', $action, array('pagination' => 'projects', 'user_id' => $user_id))
            ->setMax($max)
            ->setOrder(ProjectModel::TABLE . '.name')
            ->setQuery($this->project->getQueryColumnStats($this->projectPermission->getActiveProjectIds($user_id)))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'projects');
    }

    /**
     * Get task pagination
     *
     * @access private
     * @param  integer $user_id
     * @param  string $action
     * @param  integer $max
     * @return \Kanboard\Core\Paginator
     */
    private function getTaskPaginator($user_id, $action, $max)
    {
        return $this->paginator
            ->setUrl('app', $action, array('pagination' => 'tasks', 'user_id' => $user_id))
            ->setMax($max)
            ->setOrder('tasks.id')
            ->setQuery($this->taskFinder->getUserQuery($user_id))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'tasks');
    }

    /**
     * Get subtask pagination
     *
     * @access private
     * @param  integer $user_id
     * @param  string $action
     * @param  integer $max
     * @return \Kanboard\Core\Paginator
     */
    private function getSubtaskPaginator($user_id, $action, $max)
    {
        return $this->paginator
            ->setUrl('app', $action, array('pagination' => 'subtasks', 'user_id' => $user_id))
            ->setMax($max)
            ->setOrder('tasks.id')
            ->setQuery($this->subtask->getUserQuery($user_id, array(SubTaskModel::STATUS_TODO, SubtaskModel::STATUS_INPROGRESS)))
            ->calculateOnlyIf($this->request->getStringParam('pagination') === 'subtasks');
    }

    /**
     * Check if the user is connected
     *
     * @access public
     */
    public function status()
    {
        $this->response->text('OK');
    }

    /**
     * Dashboard overview
     *
     * @access public
     */
    public function index()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/overview', array(
            'title' => t('Dashboard'),
            'project_paginator' => $this->getProjectPaginator($user['id'], 'index', 10),
            'task_paginator' => $this->getTaskPaginator($user['id'], 'index', 10),
            'subtask_paginator' => $this->getSubtaskPaginator($user['id'], 'index', 10),
            'user' => $user,
        )));
    }

    /**
     * My tasks
     *
     * @access public
     */
    public function tasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/tasks', array(
            'title' => t('My tasks'),
            'paginator' => $this->getTaskPaginator($user['id'], 'tasks', 50),
            'user' => $user,
        )));
    }

    /**
     * My subtasks
     *
     * @access public
     */
    public function subtasks()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/subtasks', array(
            'title' => t('My subtasks'),
            'paginator' => $this->getSubtaskPaginator($user['id'], 'subtasks', 50),
            'user' => $user,
        )));
    }

    /**
     * My projects
     *
     * @access public
     */
    public function projects()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/projects', array(
            'title' => t('My projects'),
            'paginator' => $this->getProjectPaginator($user['id'], 'projects', 25),
            'user' => $user,
        )));
    }

    /**
     * My activity stream
     *
     * @access public
     */
    public function activity()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/activity', array(
            'title' => t('My activity stream'),
            'events' => $this->helper->projectActivity->getProjectsEvents($this->projectPermission->getActiveProjectIds($user['id']), 100),
            'user' => $user,
        )));
    }

    /**
     * My calendar
     *
     * @access public
     */
    public function calendar()
    {
        $this->response->html($this->helper->layout->dashboard('app/calendar', array(
            'title' => t('My calendar'),
            'user' => $this->getUser(),
        )));
    }

    /**
     * My notifications
     *
     * @access public
     */
    public function notifications()
    {
        $user = $this->getUser();

        $this->response->html($this->helper->layout->dashboard('app/notifications', array(
            'title' => t('My notifications'),
            'notifications' => $this->userUnreadNotification->getAll($user['id']),
            'user' => $user,
        )));
    }

    public function services()
    {
        $user = $this->getUser();

        $tasks = array();
        foreach ($this->task->getAllByParentClientID($user['sonder_client_id']) as $task) {
            $t = $task;
            $t['billable_hours'] = $this->sonderBillablehours->getByTaskId($task['id']);
            $tasks[] = $t;
        }

        $this->response->html($this->helper->layout->dashboard('app/services', array(
            'tasks' => $tasks,
            'title' => t('Provided services'),
            'notifications' => $this->userUnreadNotification->getAll($user['id']),
            'user' => $user,
        )));
    }

    public function assets()
    {
        $user = $this->getUser();


        $this->response->html($this->helper->layout->dashboard('app/assets', array(
            //  'tasks' => $tasks,
            'title' => t('Provided services'),
            //  'notifications' => $this->userUnreadNotification->getAll($user['id']),
            'user' => $user,
        )));
    }

    private function permissionCheck($userId, $clientId)
    {
        if ($this->sonderClientUserPermissions->existsClientUser($clientId, $userId) == "true" || $this->userSession->isAdmin()) {
            return true;
        } else {
            return false;
        }

    }

    public function domains()
    {
        $user = $this->getUser();

        $user = $this->userSession->getId();
        $domains = array();

        foreach ($this->sonderServer->getServersWithDomains() as $index => $domain) {
            if ($this->permissionCheck($user, $domain['parent_id']) || $this->permissionCheck($user, $domain['id'])) {
                $domain['credentials'] = $this->sonderCredentials->getDomainCredentialsById($domain['domainid']);
                $domains[] = $domain;
            }
        }

        $this->response->html($this->helper->layout->dashboard('asset/byservers', array(
            'data' =>
                array('servers' => $this->sonderServer->getServers(),
                    'paginator' => $domains,
                    'clients' => $this->sonderClient->getAll()
                ),
            'admin' => false,
            'servers' => $this->sonderServer->getServers(),
            'paginator' => $domains,
            'clients' => $this->sonderClient->getAll(),
            'nb_projects' => 'project',
            'title' => 'Assets / Domains',
            'sidebar_template' => 'asset/sidebar',
            'sub_template' => 'asset/byservers',
            'user' => $user
        )));

    }

    public function servers()
    {
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

        $this->response->html($this->helper->layout->dashboard('asset/server', array(
            'user' => $user,
            'admin' => false,
            'servers' => $servers,
            'clients' => $this->sonderClient->getAll(),
            'nb_projects' => 'project',
            'title' => 'Assets / Servers',
            'sidebar_template' => 'asset/sidebar',
            'sub_template' => 'asset/server'
        )));
    }
}
