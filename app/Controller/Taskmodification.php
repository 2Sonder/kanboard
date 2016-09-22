<?php

namespace Kanboard\Controller;

use Kanboard\Core\DateParser;

/**
 * Task Modification controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Taskmodification extends Base
{
    /**
     * Set automatically the start date
     *
     * @access public
     */
    public function start()
    {
        $task = $this->getTask();
        $this->taskModification->update(array('id' => $task['id'], 'date_started' => time()));
        $this->response->redirect($this->helper->url->to('task', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id'])));
    }

    /**
     * Edit description form
     *
     * @access public
     */
    public function description(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();

        if (empty($values)) {
            $values = array('id' => $task['id'], 'description' => $task['description']);
        }

        $this->response->html($this->template->render('task_modification/edit_description', array(
            'values' => $values,
            'errors' => $errors,
            'task' => $task,
        )));
    }

    /**
     * Update description
     *
     * @access public
     */
    public function updateDescription()
    {
        $task = $this->getTask();
        $values = $this->request->getValues();

        list($valid, $errors) = $this->taskValidator->validateDescriptionCreation($values);

        if ($valid) {
            if ($this->taskModification->update($values)) {
                $this->flash->success(t('Task updated successfully.'));
            } else {
                $this->flash->failure(t('Unable to update your task.'));
            }

            return $this->response->redirect($this->helper->url->to('task', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id'])), true);
        }

        $this->description($values, $errors);
    }

    /**
     * Display a form to edit a task
     *
     * @access public
     */
    public function edit(array $values = array(), array $errors = array())
    {
        $task = $this->getTask();
        $project = $this->project->getById($task['project_id']);

        if (empty($values)) {
            $values = $task;
            $values = $this->hook->merge('controller:task:form:default', $values, array('default_values' => $values));
            $values = $this->hook->merge('controller:task-modification:form:default', $values, array('default_values' => $values));
        }

        $values = $this->dateParser->format($values, array('date_due'), $this->config->get('application_date_format', DateParser::DATE_FORMAT));
        $values = $this->dateParser->format($values, array('date_started'), $this->config->get('application_datetime_format', DateParser::DATE_TIME_FORMAT));

        $t = $this->task->getById($task['id']);

        $values['sonder_client_id'] = $t['sonder_client_id'];
        $values['sonder_product_id'] = $t['sonder_product_id'];
        $values['sonder_contract_id'] = $t['sonder_contract_id'];

        $this->response->html($this->template->render('task_modification/edit_task', array(
            'project' => $project,
            'values' => $values,
            'errors' => $errors,
            'contracts' => $this->sonderContract->getList(true,$project['sonder_client_id']),
            'clients' => $this->sonderClient->getAll(),
            'products' => $this->sonderProduct->getAll(),
            'task' => $task,
            'investedhours' => $this->sonderInvestedhours->getAllByUserAndTask($task['id']),
            'billablehours' => $this->sonderBillablehours->getAllByUserAndTask($task['id']),
            'users' => $this->user->getAdmins(),
            'users_list' => $this->projectUserRole->getAssignableUsersList($task['project_id']),
            'colors_list' => $this->color->getList(),
            'categories_list' => $this->category->getList($task['project_id']),
        )));
    }

    /**
     * Validate and update a task
     *
     * @access public
     */
    public function update()
    {
        $task = $this->getTask();
        $project = $this->getProject();
        $values = $this->request->getValues();

        if($values['date_completed'] == '')
        {
            unset($values['date_completed']);
        }
        else
        {
            $values['date_completed'] = strtotime($values['date_completed']);
            if($values['date_completed'] == 0){ unset($values['date_completed']); }
        }

        $hours = array();$hours2 = array();
        $values['billable_hours'] = 0;
        foreach(array_keys($values) as $value)
        {
            $e = explode('billable_hours_',$value);
            if(isset($e[1]))
            {
                $values['billable_hours'] += $values[$value];
                $hours[] = array('user_id' => $e[1],'hours' => $values[$value]);
                unset($values[$value]);
            }

            $e2 = explode('invested_hours_',$value);
            if(isset($e2[1]))
            {
                //$values['invested_hours'] += $values[$value];
                $hours2[] = array('user_id' => $e2[1],'hours' => $values[$value]);
                unset($values[$value]);
            }

        }
        $values['sonder_parent_client_id'] = $project['sonder_client_id'];

        list($valid, $errors) = $this->taskValidator->validateModification($values);



        $task = $this->taskModification->update($values);
        foreach(array_keys($hours) as $hour)
        {
            $bh = $this->sonderBillablehours->getByTaskAndUserId($task,$hours[$hour]['user_id']);
            if(!$bh)
            {
                $bh = $hours[$hour];
                $bh['task_id'] = intval($task);
            }
            $bh['hours'] = intval($hours[$hour]['hours']);
            if($bh['hours'] > 0) {

                print_r($bh); echo '<br />';

                $this->sonderBillablehours->save($bh);
            }
        }

        foreach(array_keys($hours2) as $hour)
        {
            $bh = $this->sonderBillablehours->getByTaskAndUserId($task,$hours2[$hour]['user_id']);
            if(!$bh)
            {
                $bh = $hours2[$hour];
                $bh['task_id'] = intval($task);
            }
            $bh['hours'] = intval($hours2[$hour]['hours']);
            if($bh['hours'] > 0) {

                print_r($bh); echo '<br />';

                $this->sonderInvestedhours->save($bh);
            }
        }



        if ($valid && $task) {
            $this->flash->success(t('Task updated successfully.'));
            return $this->response->redirect($this->helper->url->to('task', 'show', array('project_id' => $task['project_id'], 'task_id' => $task['id'])), true);
        } else {
            $this->flash->failure(t('Unable to update your task.'));
            $this->edit($values, $errors);
        }
    }
}
