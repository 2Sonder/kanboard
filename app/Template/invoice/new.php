<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/js/newinvoice.js"></script>
<form class="popover-form" method="post" action="<?= $this->url->href('Invoice', 'showpdf', array('id' => $invoice['id'])) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="form-column">
     
        <?= $this->form->label(t('Client'), 'client') ?>
        <select name="sonder_client_id">
            <option value=""></option>
            <?php foreach ($clients as $client) { ?>
                <option <?php if($client['id'] == $invoice['sonder_client_id']){ echo 'selected'; } ?> value="<?= $client['id']; ?>"><?= $client['name']; ?></option>
            <?php } ?>
        </select> 
        <?= $this->form->label(t('Invoice number'), 'number') ?>
        <?= $this->form->text('number', $invoice, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>
        <?= $this->form->label(t('Beschrijving top'), 'beschrijvingtop') ?>
        <?= $this->form->textarea('beschrijvingtop', $invoice, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>
        <div>
            <table class="table-fixed table-medium">
                <tr>
                    <th class="column-30">Product</th>
                    <th class="column-30">Description</th>
                    <th class="column-10">Price</th>
                    <th class="column-10">Quantity</th>
                    <th class="column-10">Discount</th>
                    <th class="column-10">Total</th>
                    <th class="column-10"></th>
                </tr>
                <?php $total = 0; foreach ($lines as $index => $line) { ?>
                    <tr>
                        <td><input type="text" class="product" name="product_<?php echo $index; ?>" value="<?php echo $line['product']['title']; ?>" /></td>
                        <td><input type="text" class="description" name="description_<?php echo $index; ?>" value="<?php echo $line['description']; ?>" /></td>
                        <td><input type="text" class="price" name="price_<?php echo $index; ?>" value="<?php echo number_format((float)$line['price'], 2, ',', ''); ?>" /></td>
                        <td><input type="text" class="quantity" name="quantity_<?php echo $index; ?>" value="<?php echo $line['quantity']; ?>" /></td>
                        <td><input type="text" class="discount" name="discount_<?php echo $index; ?>" value="<?php echo $line['discount']; ?>" /></td>
                        <td><input type="text" class="total" name="total_<?php echo $index; ?>" value="<?php echo number_format((float)$line['total'], 2, ',', ''); ?>" /></td>
                        <td></td>
                    </tr>
                <?php $total += floatval($line['total']);  } ?>
        <!--        <tr>
                    <td><input type="text" class="product"  name="product" value="" /></td>
                    <td><input type="text" class="description"  name="description" value="" /></td>
                    <td><input type="text" class="price" name="price" value="" /></td>
                    <td><input type="text" class="quantity" name="quantity" value="" /></td>
                    <td><input type="text" class="discount" name="discount" value="" /></td>
                    <td></td>
                    <td></td>
                    <td><input type="submit"  value="add" /></td>
                </tr>-->
                <tr>
                    <td colspan="4"></td>
                    <td>
                        Subtotal: <br />
                        Tax:  <br />
                        Total:  <br />
                    </td>
                    <td>
                        <span id="totalsex"><?php echo number_format((float)$total, 2, ',', ''); ?></span><br />
                        <span id="tax"><?php $tax = ($total / 100) * 21; echo number_format((float)$tax, 2, ',', ''); ?></span><br />
                        <span id="totalsinc"><?php echo number_format((float)($total + $tax), 2, ',', ''); ?></span><br />
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?= $this->form->label(t('Beschrijving bottom'), 'beschrijvingbottom') ?>
        <?= $this->form->textarea('beschrijvingbottom', $invoice, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <div class="form-actions">
            <button type="submit" class="btn btn-blue" tabindex="15"><?= t('Download PDF') ?></button>
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'invoice', 'index', array()); ?>
        </div>
</form>
