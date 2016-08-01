<div class="page-header">
    <h2><?= t('New task') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('taskcreation', 'save', array('project_id' => $values['project_id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <div class="form-column">
        <?= $this->form->label(t('Title'), 'title') ?>
        <?= $this->form->text('title', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Description'), 'description') ?>
        <?= $this->form->textarea(
            'description',
            $values,
            $errors,
            array(
                'placeholder="'.t('Leave a description').'"',
                'tabindex="2"',
                'data-mention-search-url="'.$this->url->href('UserHelper', 'mention', array('project_id' => $values['project_id'])).'"'
            ),
            'markdown-editor'
        ) ?>

        <?= $this->render('task/color_picker', array('colors_list' => $colors_list, 'values' => $values)) ?>

        <?php if (! isset($duplicate)): ?>
            <?= $this->form->checkbox('another_task', t('Create another task'), 1, isset($values['another_task']) && $values['another_task'] == 1) ?>
        <?php endif ?>

        <?= $this->hook->render('template:task:form:left-column', array('values' => $values, 'errors' => $errors)) ?>
    </div>

    <div class="form-column">
        <?= $this->form->hidden('project_id', $values) ?>
        <?= $this->task->selectAssignee($users_list, $values, $errors) ?>
        <?= $this->task->selectCategory($categories_list, $values, $errors) ?>
        <?= $this->task->selectSwimlane($swimlanes_list, $values, $errors) ?>
        <?= $this->task->selectColumn($columns_list, $values, $errors)  ?>
        <?= $this->task->selectPriority($project, $values) ?>
        <?= $this->task->selectScore($values, $errors) ?>
        <?= $this->task->selectTimeEstimated($values, $errors) ?>
        <?= $this->task->selectDueDate($values, $errors) ?>
        
        <?= $this->form->label(t('Billable hours'), 'billable_hours') ?>
        <table>
            <?php foreach($users as $user){ ?>
                <tr>
                    <td><?php echo $user['name']; ?>(<?php echo $user['email']; ?>)</td>
                    <td><input type="number" pattern="[0-9]+([\.,][0-9]+)?" step="0.5" name="billable_hours_<?php echo $user['id']; ?>" value="0" /></td>
                </tr>
            <? } ?>
        </table>

        <?= $this->form->label(t('Client (Client van onze klant)'), 'sonder_client_id') ?>
        <select name="sonder_client_id">
            <option value=""></option>
            <?php foreach($clients as $client){ ?>
            <option value="<?php echo $client['id']; ?>"><?php echo $client['name']; ?></option>
            <?php } ?>
        </select>
        
        <?= $this->form->label(t('Product (uurtarief)'), 'sonder_product_id') ?>
        <select name="sonder_product_id">
            <option value=""></option>
            <?php foreach($products as $product){ ?>
            <option value="<?php echo $product['id']; ?>"><?php echo $product['title']; ?></option>
            <?php } ?>
        </select>
        <?= $this->hook->render('template:task:form:right-column', array('values' => $values, 'errors' => $errors)) ?>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'board', 'show', array('project_id' => $values['project_id']), false, 'close-popover') ?>
    </div>
</form>
