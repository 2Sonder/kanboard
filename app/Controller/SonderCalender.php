<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/7/2016
 * Time: 1:17 AM
 */

namespace Kanboard\Controller;


class SonderCalender extends Base
{

    /**
     * Display a form to create a new task
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
      //  $project = $this->getProject();
      //  $swimlanes_list = $this->swimlane->getList($project['id'], false, true);

        $project = array();
        $swimlanes_list = array();

        if (empty($values)) {
            $values = array(
                'swimlane_id' => $this->request->getIntegerParam('swimlane_id', key($swimlanes_list)),
                'column_id' => $this->request->getIntegerParam('column_id'),
                'color_id' => $this->color->getDefaultColor(),
                'owner_id' => $this->userSession->getId(),
            );

            $values = $this->hook->merge('controller:task:form:default', $values, array('default_values' => $values));
            $values = $this->hook->merge('controller:task-creation:form:default', $values, array('default_values' => $values));
        }

        $values['project_id'] = 'x';
        $values['color_id'] = 'green';

        $users = [];
        foreach($this->user->getAll() as $user)
        {
            $users[$user['id'].' '] = $this->user->getFullname($user);
        }



        $this->response->html($this->template->render('invoice/calendar', array(
            'project' => $project,
            'errors' => $errors,
            'contracts' => array(),
            'clients' => $this->sonderClient->getAll(),
            'products' => $this->sonderProduct->getAll(),
            'values' => $values ,
            'columns_list' => array(),
            'users_list' => $users,
            'colors_list' => $this->color->getList(),
            'categories_list' => array(),
            'swimlanes_list' => $swimlanes_list,
            'title' =>  t('New task')
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        $values['date_due'] = strtotime($values['date_due']);
        $values['owner_id'] = trim($values['owner_id']);
        $values['column_id'] = 73;
        $values['project_id'] = 1;


        $this->task->save($values);

        $this->response->redirect($this->helper->url->to('app', 'calendar', array('user_id' => $user['id'])));
    }

}