<h2><?= t('Contracts') ?></h2>
<div>
    <form action="/?controller=invoice&action=savecontract" method="POST" />
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
        <?php foreach ($contracts as $index => $contract){ ?>
        <tr>
            <td> <a href="/?controller=invoice&action=editcontract&id=<?php echo $contract['id']; ?>">edit</a> </td>
            <td><?php echo $contract['id']; ?></td>
            <td><?php echo $contract['clientname']; ?></td>
            <td><?php echo $contract['name']; ?></td>
            <td><?php echo $contract['uren']; ?></td>
            <td><?php echo date('Y-m-d',strtotime($contract['creation_date'])); ?></td>
            <td><?php echo $contract['description']; ?></td>
            <td><?php echo $contract['productname']; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td><?= $this->form->select('sonder_client_id', $clients , array(), array(), array('required'), 'form-input-large'); ?></td>
            <td><input type="text" name="name" value=""  /></td>
            <td><input type="text" name="uren" value=""  /></td>
            <td><?= $this->helper->form->text('creation_date', array(), array(), array(), 'form-date') ?></td>
            <td><?= $this->form->text('description', array(), array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?></td>
            <td><?= $this->form->select('sonder_product_id', $products , array(), array(), array('required'), 'form-input-large'); ?></td>
            <td>
                <input type="submit" class="btn btn-info" value="Save" />
            </td>
        </tr>
    </table>
    </form>
</div>
