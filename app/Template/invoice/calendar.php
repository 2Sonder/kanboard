<div class="page-header">
    <h2><?= t('New appointment') ?></h2>
</div>
<form class="popover-form" method="post" style="width: 100%;"
      action="<?= $this->url->href('sonderCalender', 'save', array()) ?>"
      autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="form-column" style="min-width: 100%;">
        <?= $this->form->label(t('Title'), 'title') ?>
        <?= $this->form->text('title', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>
        <?= $this->form->label(t('Description'), 'description') ?>
        <?= $this->form->textarea(
            'description',
            $values,
            $errors,
            array(
                'placeholder="' . t('Leave a description') . '"',
                'tabindex="2"',
                'data-mention-search-url="' . $this->url->href('UserHelper', 'mention', array('project_id' => $values['project_id'])) . '"',
                'required'
            ),
            'markdown-editor'
        ) ?>
        <?= $this->render('task/color_picker', array('colors_list' => $colors_list, 'values' => $values)) ?>
        <?= $this->hook->render('template:task:form:left-column', array('values' => $values, 'errors' => $errors)) ?>
        <?= $this->form->hidden('project_id', array('project_id' => "0")) ?>
        <?= $this->helper->form->label('Date', 'due_date'); ?>
        <?= $this->helper->form->text('date_due', $values, $errors, array(), 'form-date'); ?>
        <?= $this->task->selectAssignee($users_list, $values, $errors) ?>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'board', 'show', array('project_id' => $values['project_id']), false, 'close-popover') ?>
    </div>
</form>


