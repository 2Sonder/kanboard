<?php

namespace Kanboard\Controller;

/**
 * Task Popover
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class TaskPopover extends Base
{
    /**
     * Change a task assignee directly from the board
     *
     * @access public
     */
    public function changeAssignee()
    {
        $task = $this->getTask();
        $project = $this->project->getById($task['project_id']);

        $this->response->html($this->template->render('task_popover/change_assignee', array(
            'values'     => $task,
            'users_list' => $this->projectUserRole->getAssignableUsersList($project['id']),
            'project'    => $project,
        )));
    }

    /**
     * Validate an assignee modification
     *
     * @access public
     */
    public function updateAssignee()
    {
        $values = $this->request->getValues();

        list($valid,) = $this->taskValidator->validateAssigneeModification($values);

        if ($valid && $this->taskModification->update($values)) {
            $this->flash->success(t('Task updated successfully.'));
        } else {
            $this->flash->failure(t('Unable to update your task.'));
        }

        $this->response->redirect($this->helper->url->to('board', 'show', array('project_id' => $values['project_id'])), true);
    }

    /**
     * Change a task category directly from the board
     *
     * @access public
     */
    public function changeCategory()
    {
        $task = $this->getTask();
        $project = $this->project->getById($task['project_id']);

        $this->response->html($this->template->render('task_popover/change_category', array(
            'values'          => $task,
            'categories_list' => $this->category->getList($project['id']),
            'project'         => $project,
        )));
    }

    /**
     * Validate a category modification
     *
     * @access public
     */
    public function updateCategory()
    {
        $values = $this->request->getValues();

        list($valid,) = $this->taskValidator->validateCategoryModification($values);

        if ($valid && $this->taskModification->update($values)) {
            $this->flash->success(t('Task updated successfully.'));
        } else {
            $this->flash->failure(t('Unable to update your task.'));
        }

        $this->response->redirect($this->helper->url->to('board', 'show', array('project_id' => $values['project_id'])), true);
    }

    /**
     * Screenshot popover
     *
     * @access public
     */
    public function screenshot()
    {
        $task = $this->getTask();

        $this->response->html($this->template->render('task_file/screenshot', array(
            'task' => $task,
        )));
    }
}
