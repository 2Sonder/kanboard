<form action="/?controller=invoice&action=savecategory" method="POST"/>
    <?= $this->form->csrf(); ?>
    <table>
        <tr>
            <th class="column-10">Name</th>
            <th></th>
        </tr>
        <tr>
            <td><input type="text" name="name" value="<?php echo $category['name']; ?>"/></td>
            <td>
                <input type="hidden" name="sonder_entity_name" value="sonder_ledger"/>
                <input type="hidden" name="sonder_entity_id" value="<?php echo $category['id']; ?>"/>
                <input type="submit" class="btn btn-info" value="Save category"/>
            </td>
        </tr>
    </table>
</form>