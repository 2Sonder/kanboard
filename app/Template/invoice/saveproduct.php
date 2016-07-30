<form action="/?controller=invoice&action=saveproduct" method="POST"/>
    <?= $this->form->csrf(); ?>
    <table>
        <tr>
            <td></td>
            <td></td>
            <td><input type="text" name="title" value="<?php echo $product['title']; ?>"/></td>
            <td><input type="text" name="price" value="<?php echo $product['price']; ?>"/></td>
            <td>
                <input type="hidden" name="sonder_entity_name" value="sonder_product"/>
                <input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>"/>
                <input type="submit" class="btn btn-info" value="Edit product"/>
            </td>
        </tr>
    </table>
</form>