<script src="/assets/js/app.js?1470871850" type="text/javascript"></script>
<div class="page-header">
    <h2><?= t('Close a task') ?></h2>
</div>
<form class="popover-form" method="get" action="<?= $this->url->href('taskstatus', 'closeTaskByDate', array('saving' => 'saving','task_id' => $task['id'],'project_id' => $task['project_id'], 'confirmation' => 'yes')) ?>" autocomplete="off">
<?= $this->url->href('taskstatus', 'close', array('saving' => 'saving','task_id' => $task['id'],'project_id' => $task['project_id'], 'confirmation' => 'yes')) ?>" autocomplete="off">
    <div class="confirm">
        <p class="alert alert-info">
            <?= t('Do you really want to close the task "%s" as well as all subtasks?', $task['title']) ?>
        </p>
        <div class="form-actions">
            <?php echo $this->helper->form->text('date_due', array('date_due' => date('Y-m-d')), array(), array(), 'form-date'); ?><br />
         <!--   <input type="submit" value="Yes" class="btn btn-red popover-link" />-->
            <a id="close-task" class="btn btn-red popover-link" >Yes</a>
         <!--   <?php $this->url->link(t('Yes'), 'taskstatus', 'close', array('task_id' => $task['id'], 'project_id' => $task['project_id'], 'confirmation' => 'yes'), true, 'btn btn-red popover-link') ?>-->
            <?= t('or') ?>
            <?= $this->url->link(t('cancel'), 'task', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id']), false, 'close-popover') ?>
        </div>
    </div>
</form>