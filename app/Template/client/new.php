<? if(isset($_GET['client_id'])){ $clientid = $_GET['client_id']; }else{ $clientid = 0; } ?>
<form class="popover-form" method="post" action="<?= $this->url->href('client', 'save', array('client_id' => $clientid)) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="form-column">

        <?= $this->form->label(t('Name'), 'name') ?>
        <input type="text" name="name" value="<?php echo $client['name']; ?>" />
        
        <?= $this->form->label(t('Relatienummer'), 'number') ?>
        <input type="text" name="number" value="<?php echo $client['number']; ?>" />
        
        <?= $this->form->label(t('Email'), 'email') ?>
        <input type="text" name="email" value="<?php echo $client['email']; ?>" />

        <?= $this->form->label(t('Technical email'), 'technicalemail') ?>
        <?= $this->form->text('technicalemail', $client, $errors, array('maxlength="50"')) ?>

        <?= $this->form->label(t('Administrative email'), 'administrativeemail') ?>
        <?= $this->form->text('administrativeemail', $client, $errors, array('maxlength="50"')) ?>

        <?= $this->form->label(t('Name contact'), 'contact') ?>
        <?= $this->form->text('contact', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Description'), 'description') ?>
        <?= $this->form->text('description', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Adres'), 'adres') ?>
        <?= $this->form->text('adres', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Postcode'), 'postcode') ?>
        <?= $this->form->text('postcode', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('City'), 'city') ?>
        <?= $this->form->text('city', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <?= $this->form->label(t('Department'), 'department') ?>
        <?= $this->form->text('department', $client, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>


        <?= $this->form->label(t('Parent client'), 'client'); ?>
        <select name="parent_id">
            <option value=""></option>
            <?php foreach ($clients as $c) { ?>
                <option <?php if($c['id'] == $client['parent_id']){ echo 'selected'; } ?> value="<?= $c['id']; ?>"><?= $c['name']; ?></option>
            <?php } ?>
        </select>

        <?= $this->form->label(t('Permission for use'), 'permission'); ?>

        <select name="permission[]" multiple>
            <?php foreach ($users as $user) { ?>
                <?php
                $selected = false;
                foreach ($permissions as $per)
                    if (isset($per['user_id']) && $per['user_id'] == $user['id'])
                        $selected = true;
                ?>
                <option <?php if($selected){ echo 'selected'; } ?> value="<?= $user['id']; ?>"><?= $user['username']; ?></option>
            <?php } ?>
        </select>

<div class="form-actions">
    <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Save') ?></button>
<?= t('or') ?> <?= $this->url->link(t('Cancel'), 'client', 'index', array()); ?>
</div>
</form>
        <div class="page-header">
            <h2>Credentials</h2>
        </div>
        <form action="/?controller=credentials&action=save" method="POST" />
        <?= $this->form->csrf(); ?>
        <table class="table-fixed table-small">
            <tr>
                <th class="column-10">type</th>
                <th class="column-10">url</th>
                <th class="column-10">user</th>
                <th class="column-8">password</th>
                <th class="column-10"></th>
            </tr>
            <?php foreach ($credentials as $index => $cred) { 
                  
                ?>
                <tr>
                    <td><input type="text" name="type_<?php echo $index; ?>" value="<?php echo $cred['type']; ?>"  /></td>
                    <td><input type="hidden" name="id_<?php echo $index; ?>" value="<?php echo $cred['id']; ?>" />
                        <input type="text" name="url_<?php echo $index; ?>" value="<?php echo $cred['url']; ?>"  /></td>
                    <td><input type="text" name="user_<?php echo $index; ?>" value="<?php echo $cred['user']; ?>"  /></td>
                    <td><input type="text" name="password_<?php echo $index; ?>" value="<?php echo $cred['password']; ?>"  /></td>
                    <td><input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>" />
                        <input type="hidden" name="sonder_entity_name" value="sonder_client" />
                        <input type="submit" value="Edit" />/
                        <button formaction="/?controller=credentials&action=remove&id=<?php echo $client['id']; ?>">Remove</button></td>
                </tr>
            <?php } ?>
            <tr>
                <input type="hidden" name="index" value="<?php
                if (isset($index)) {
                    echo $index;
                } else {
                    echo '-1';
                }
                ?>" />
                <td><input type="text" name="type" value=""  /></td>
                <td><input type="text" name="url" value=""  /></td>
                <td><input type="text" name="user" value=""  /></td>
                <td><input type="text" name="password" value=""  /></td>
                <td>
                    <input type="hidden" name="sonder_entity_name" value="sonder_client" />
                    <input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>" />
                    <input type="submit" value="add passwords" />
                </td>
            </tr>    
        </table>
</form>


