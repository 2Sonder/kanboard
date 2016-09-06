<h2><?= t('Acquisition categories') ?></h2>
<div>
    <form action="/?controller=invoice&action=saveacquisition" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-10">Name</th>
            <th class="column-5"></th>
        </tr>
        <?php foreach ($ledger as $index => $dc) { ?>
        <tr>
            <td><a href="/?controller=invoice&action=saveacquisition&id=<?php echo $dc['id']; ?>">edit</a></td>
            <td><?php echo $dc['id']; ?></td>
            <td><?php echo $dc['name']; ?></td>
            <td></td>
        </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td><input type="text" name="name" value=""  /></td>
            <td>
                <input type="hidden" name="sonder_entity_name" value="sonder_client" />
                <input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>" />
                <input type="submit" class="btn btn-info" value="Add category" />
            </td>
        </tr>    
    </table>
</form>
</div>

<h2><?= t('Products') ?></h2>
<div>
    <form action="/?controller=invoice&action=saveproduct" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-10">Title</th>
            <th class="column-10">Price</th>
            <th class="column-5"></th>
        </tr>
        <?php foreach ($products as $index => $dc) { ?>
            <tr>
                <td><a href="/?controller=invoice&action=saveproduct&id=<?php echo $dc['id']; ?>">edit</a></td>
                <td><?php echo $dc['id']; ?></td>
                <td><?php echo $dc['title']; ?></td>
                <td><?php echo $dc['price']; ?></td>
                <td></td>
            </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td><input type="text" name="title" value=""  /></td>
            <td><input type="text" name="price" value=""  /></td>
            <td>
                <input type="hidden" name="sonder_entity_name" value="sonder_client" />
                <input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>" />

                <input type="submit" class="btn btn-info" value="Add product" />
            </td>
        </tr>    
    </table>
</form>
</div>
