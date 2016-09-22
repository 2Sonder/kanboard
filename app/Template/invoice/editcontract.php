<h2><?= t('Edit contract') ?></h2>
<div>
    <form action="/?controller=invoice&action=savecontract" method="POST"/>
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-10">Client</th>
            <th class="column-10">Name</th>
            <th class="column-10">Uren</th>
            <th class="column-10">Creation_date</th>
            <th class="column-10">Description</th>
            <th class="column-10">Rate</th>
            <th class="column-5"></th>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><?= $this->form->select('sonder_client_id', $clients, $contract, array(), array('required'), 'form-input-large'); ?></td>
            <td><input type="text" name="name" value="<?php echo $contract['name']; ?>"/></td>
            <td><input type="text" name="uren" value="<?php echo $contract['uren']; ?>"/></td>
            <td><?= $this->helper->form->text('creation_date', $contract, array(), array(), 'form-date') ?></td>
            <td><?= $this->form->text('description', $contract, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?></td>
            <td><?= $this->form->select('sonder_product_id', $products, $contract, array(), array('required'), 'form-input-large'); ?></td>
            <td>
                <input type="hidden" name="id" value="<?php echo $contract['id']; ?>"/>
                <input type="submit" class="btn btn-info" value="Save"/>
            </td>
        </tr>
    </table>
    </form>
</div>
