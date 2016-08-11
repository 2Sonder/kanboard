<?php

namespace Kanboard\Controller;

/**
 * Task Status controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Taskstatus extends Base
{
    /**
     * Close a task
     *
     * @access public
     */

    public function closeTaskByDate()
    {
        die('closing');
    }


    public function close()
    {

        /*
        $values = $this->request->getValues();
        print_r($_GET);

        print_r($_POST);

        print_r($values);

        if(isset($_GET['saving']))
        {
            die('saving');
        }*/

       $this->changeStatus('close', 'task_status/close', t('Task closed successfully.'), t('Unable to close this task.'));
    }

    /**
     * Open a task
     *
     * @access public
     */
    public function open()
    {
        $this->changeStatus('open', 'task_status/open', t('Task opened successfully.'), t('Unable to open this task.'));
    }

    /**
     * Common method to change status
     *
     * @access private
     * @param  string $method
     * @param  string $template
     * @param  string $success_message
     * @param  string $failure_message
     */
    private function changeStatus($method, $template, $success_message, $failure_message)
    {
        $task = $this->getTask();
     //   die('here');

        if ($this->request->getStringParam('confirmation') === 'yes') {

            $this->checkCSRFParam();

            if ($method == 'close') {

                $values = $this->request->getValues();
                if ($this->taskStatus->close($task['id'],strtotime($values['date_due']))) {
                        $this->flash->success($success_message);
                } else {
                    $this->flash->failure($failure_message);
                }
            } else {
                if ($this->taskStatus->$method($task['id'])) {
                    $this->flash->success($success_message);
                } else {
                    $this->flash->failure($failure_message);
                }

            }


          //  return $this->response->redirect($this->helper->url->to('task', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id'])), true);
        }


        $this->response->html($this->template->render($template, array(
            'task' => $task,
        )));
    }
}
