<div class="page-header">
    <h2><?= t('Edit a task') ?></h2>
</div>
<form class="popover-form" method="post"
      action="<?= $this->url->href('taskmodification', 'update', array('task_id' => $task['id'], 'project_id' => $task['project_id'])) ?>"
      autocomplete="off">

    <?= $this->form->csrf() ?>
    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('project_id', $values) ?>

    <div class="form-column">
        <?= $this->form->label(t('Title'), 'title') ?>
        <?= $this->form->text('title', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"')) ?>
        <?= $this->task->selectAssignee($users_list, $values, $errors) ?>
        <?= $this->task->selectCategory($categories_list, $values, $errors) ?>
        <?= $this->task->selectPriority($project, $values) ?>
        <?= $this->task->selectScore($values, $errors) ?>

        <?= $this->hook->render('template:task:form:left-column', array('values' => $values, 'errors' => $errors)) ?>

        <?php if ($this->user->isSuperAdmin()) { ?>

            <?= $this->form->label(t('Only for the sysadmin'), 'date_completed') ?>

            <?= $this->task->selectCloseDate($values, $errors, array()) ?>


        <?php } ?>

        <?= $this->form->label(t('Invested hours'), 'invested_hours') ?>
        <table>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['name']; ?>(<?php echo $user['email']; ?>)</td>
                    <td><input type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.5"
                               name="invested_hours_<?php echo $user['id']; ?>"
                               value="<?php if(isset($investedhours[$user['id']]['hours']) && $investedhours[$user['id']]['hours']>0){ echo $investedhours[$user['id']]['hours']; }else{ echo '0'; } ?>"/></td>
                </tr>
            <? } ?>
        </table>


    </div>

    <div class="form-column">
        <?= $this->task->selectTimeEstimated($values, $errors) ?>
        <?= $this->task->selectTimeSpent($values, $errors) ?>
        <?= $this->task->selectStartDate($values, $errors) ?>
        <?= $this->task->selectDueDate($values, $errors) ?>

        <?php //print_r($values); ?>


        <?= $this->form->label(t('Billable hours'), 'billable_hours') ?>
        <table>
            <?php foreach ($users as $user) { ?>
                <tr>
                    <td><?php echo $user['name']; ?>(<?php echo $user['email']; ?>)</td>
                    <td><input type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.5"
                               name="billable_hours_<?php echo $user['id']; ?>"
                               value="<?php echo $billablehours[$user['id']]['hours']; ?>"/></td>
                </tr>
            <? } ?>
        </table>


        <?= $this->form->label(t('Client (Client van onze klant)'), 'sonder_client_id') ?>
        <select name="sonder_client_id">
            <option value=""></option>
            <?php foreach ($clients as $client) { ?>
                <option <?php if ($client['id'] == $values['sonder_client_id']) {
                    echo 'selected';
                } ?> value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
            <?php } ?>
        </select>

        <?= $this->form->label(t('Product (uurtarief)'), 'sonder_product_id') ?>
        <select name="sonder_product_id">
            <option value=""></option>
            <?php foreach ($products as $product) { ?>
                <option <?php if ($product['id'] == $values['sonder_product_id']) {
                    echo 'selected';
                } ?> value="<?php echo $product['id']; ?>"><?php echo $product['title']; ?></option>
            <?php } ?>
        </select>

        <?= $this->hook->render('template:task:form:right-column', array('values' => $values, 'errors' => $errors)) ?>
    </div>

    <div class="form-clear">
        <?= $this->render('task/color_picker', array('colors_list' => $colors_list, 'values' => $values)) ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'task', 'show', array('task_id' => $task['id'], 'project_id' => $task['project_id']), false, 'close-popover') ?>
    </div>
</form>
