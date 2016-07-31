<?php

namespace Kanboard\Controller;

/**
 * Task Creation controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Taskcreation extends Base
{
    /**
     * Display a form to create a new task
     *
     * @access public
     */
    public function create(array $values = array(), array $errors = array())
    {
        $project = $this->getProject();
        $swimlanes_list = $this->swimlane->getList($project['id'], false, true);

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

        $this->response->html($this->template->render('task_creation/form', array(
            'project' => $project,
            'errors' => $errors,
            'clients' => $this->sonderClient->getAll(),
            'products' => $this->sonderProduct->getAll(),
            'users' => $this->user->getAdmins(),
            'values' => $values + array('project_id' => $project['id']),
            'columns_list' => $this->column->getList($project['id']),
            'users_list' => $this->projectUserRole->getAssignableUsersList($project['id'], true, false, true),
            'colors_list' => $this->color->getList(),
            'categories_list' => $this->category->getList($project['id']),
            'swimlanes_list' => $swimlanes_list,
            'title' => $project['name'].' &gt; '.t('New task')
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getValues();

        $hours = array();
        $values['billable_hours'] = 0;
        foreach(array_keys($values) as $value)
        {
            $e =explode('billable_hours_',$value);
            if(isset($e[1]))
            {
                $values['billable_hours'] += $values[$value];
                $hours[] = array('user_id' => $e[1],'hours' => $values[$value]);

                unset($values[$value]);
            }
        }
        $values['sonder_parent_client_id'] = $project['sonder_client_id'];

        list($valid, $errors) = $this->taskValidator->validateCreation($values);

        $task = $this->taskCreation->create($values);
        foreach(array_keys($hours) as $hour)
        {
            $bh = $hours[$hour];
            $bh['task_id'] = intval($task);
            $this->sonderBillablehours->save($bh);
        }

        if ($valid && $task) {
            $this->flash->success(t('Task created successfully.'));
            return $this->afterSave($project, $values);
        }

        $this->flash->failure(t('Unable to create your task.'));
        $this->create($values, $errors);
    }

    private function afterSave(array $project, array &$values)
    {
        if (isset($values['another_task']) && $values['another_task'] == 1) {
            return $this->create(array(
                'owner_id' => $values['owner_id'],
                'color_id' => $values['color_id'],
                'category_id' => isset($values['category_id']) ? $values['category_id'] : 0,
                'column_id' => $values['column_id'],
                'swimlane_id' => isset($values['swimlane_id']) ? $values['swimlane_id'] : 0,
                'another_task' => 1,
            ));
        }
        $this->response->redirect($this->helper->url->to('board', 'show', array('project_id' => $project['id'])));
    }
}
