<div class="page-header">
    <h2><?= t('New task') ?></h2>
</div>

<form class="popover-form" method="post" action="<?= $this->url->href('client', 'save', array()) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <div class="form-column">
        <?php 
        $values = array();
        $errors = array();
        ?>
        
        <?= $this->form->label(t('Name'), 'name') ?>
        <?= $this->form->text('name', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Relatienummer'), 'number') ?>
        <?= $this->form->text('number', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Email'), 'email') ?>
        <?= $this->form->text('email', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Name contact'), 'contact') ?>
        <?= $this->form->text('contact', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Description'), 'description') ?>
        <?= $this->form->text('description', $values, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?> 
    <div class="form-actions">
        <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Save') ?></button>
        <?= t('or') ?> <?= $this->url->link(t('cancel'), 'client', 'index', array()); ?>
    </div>
</form>
