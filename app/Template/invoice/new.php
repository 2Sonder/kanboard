<script type="text/javascript" src="http://<?php echo $_SERVER['HTTP_HOST'] ?>/js/newinvoice.js"></script>
<form class="popover-form" method="post" action="<?= $this->url->href('Invoice', 'showpdf', array('id' => $invoice['id'])) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div class="form-column">
     <style>
         .right
         {
             text-align: right;
         }
     </style>
        <?= $this->form->label(t('Client'), 'client') ?>
        <select name="sonder_client_id">
            <option value=""></option>
            <?php foreach ($clients as $client) { ?>
                <option <?php if($client['id'] == $invoice['sonder_client_id']){ echo 'selected'; } ?> value="<?= $client['id']; ?>"><?= $client['name']; ?></option>
            <?php } ?>
        </select>

        <?= $this->form->label(t('Status'), 'status') ?>
        <?= $this->form->select('status', $statusoptions , $invoice, $errors, array('required'), 'form-input-large') ?>


        <?= $this->form->label(t('Invoice number'), 'number') ?>
        <?= $this->form->text('number', $invoice, $errors, array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>
        <?= $this->form->label(t('Beschrijving top'), 'beschrijvingtop') ?>
        <?= $this->form->textarea('beschrijvingtop', $invoice, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>
        <div>
            <table class="table-fixed table-medium">
                <tr>
                    <th class="column-10"></th>
                    <th class="column-30">Product</th>
                    <th class="column-30">Description</th>
                    <th class="column-10">Price</th>
                    <th class="column-10">Quantity</th>
                    <th class="column-10">Discount</th>
                    <th class="column-10">Total</th>
                    <th class="column-10"></th>
                </tr>
                <?php $linecount = 0; $total = 0; foreach ($lines as $index => $line) { ?>
                <tr id="invoiceLine_<?php echo $index; ?>">
                    <td><input type="hidden"  value="<?php echo $line['id']; ?>" name="id_<?php echo $index; ?>"><a id="<?php echo $index; ?>" class="DeleteInvoiceLine">Delete</a></td>
                    <td><input type="text" class="product" name="product_<?php echo $index; ?>" value="<?php echo $line['product']['title']; ?>" /></td>
                    <td><input type="text" class="description" name="description_<?php echo $index; ?>" value="<?php echo $line['description']; ?>" /></td>
                    <td><input type="text" class="right" class="price" name="price_<?php echo $index; ?>" value="<?php echo number_format((float)$line['price'], 2, ',', ''); ?>" /></td>
                    <td><input type="text" class="right" class="quantity" name="quantity_<?php echo $index; ?>" value="<?php echo $line['quantity']; ?>" /></td>
                    <td><input type="text" class="right" class="discount" name="discount_<?php echo $index; ?>" value="<?php echo $line['discount']; ?>" /></td>
                    <td><input type="text" class="right" class="total" name="total_<?php echo $index; ?>" value="<?php echo number_format((float)$line['total'], 2, ',', ''); ?>" /></td>
                    <td></td>
                </tr>
                <?php $total += floatval($line['total']); if($line['id'] > $linecount){$linecount=$line['id'];}  } ?>
                <tr>
                    <td colspan="4"><input class="right" type="hidden" name="linecount" id="linecount" value="<?php echo $linecount; ?>" /></td>
                    <td>Discount: </td>
                    <td><input class="right" type="text" name="percentdiscount" value="0" /></td>
                    <td><input class="right" type="text" name="discount" value="<?php echo number_format(0, 2, ',', ''); ?>" /></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td>
                        Subtotal: <br />
                        Tax:  <br />
                        Total:  <br />
                    </td>
                    <td class="right">
                        <span class="right" id="totalsex"><?php echo number_format((float)$total, 2, ',', ''); ?></span><br />
                        <span class="right" id="tax"><?php $tax = ($total / 100) * 21; echo number_format((float)$tax, 2, ',', ''); ?></span><br />
                        <span class="right" id="totalsinc"><?php echo number_format((float)($total + $tax), 2, ',', ''); ?></span><br />
                    </td>
                    <td></td>
                </tr>
            </table>
        </div>
        <?= $this->form->label(t('Beschrijving bottom'), 'beschrijvingbottom') ?>
        <?= $this->form->textarea('beschrijvingbottom', $invoice, $errors, array('autofocus', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>

        <div class="form-actions">
            <input type="submit" name="save" value="<?= t('Save'); ?>" class="btn btn-blue" tabindex="15" />
            <input type="submit" name="save" value="<?= t('Download PDF'); ?>" class="btn btn-blue" tabindex="15" />
            <?= t('or') ?> <?= $this->url->link(t('cancel'), 'invoice', 'index', array()); ?>
        </div>
</form>
