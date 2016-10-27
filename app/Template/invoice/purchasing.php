
<h2>Upload transaction log</h2>
<div>
    <form class="popover-form" action="/?controller=invoice&action=savefile" enctype="multipart/form-data" method="POST" autocomplete="off">
    <?= $this->form->csrf() ?>
        Upload MT940: <input class="btn" type="file" name="fileToUpload" id="fileToUpload" />
    <input class="btn btn-info" type="submit" value="Upload bestand" />
    </form>
    <br />
</div>
<div>
    <?= $this->form->select('dc', $dc , array(), array(), array('required'), 'form-input-large') ?>
</div>
<h2>Open statement</h2>
<table class="table-fixed table-small">
    <tr>
        <th class="column-3"></th>
        <th class="column-3">#</th>
        <th class="column-3">D/C</th>
        <th class="column-25">Description</th>
        <th class="column-5">Entrytime</th>

        <th class="column-5">Price</th>
        <th class="column-10">Account</th>
        <th class="column-10">Account Nr</th>

        <th class="column-10">Ledger</th>
        <th class="column-5">Extax</th>
        <th class="column-5">Inctax</th>
    </tr>
    <?php foreach ($openstatements as $index => $dc) { ?>
        <tr class="<?php echo $dc['debitcredit']; ?> <?php echo $dc['debitcredit']; ?>">
            <td><a href="/?controller=invoice&action=editpurchasing&id=<?php echo $dc['id']; ?>">Edit</a></td>
            <td><?php echo $dc['id']; ?></td>
            <td><?php echo $dc['debitcredit']; ?></td>
            <td><?php echo $dc['description']; ?></td>
            <td><?php echo date('d-m-Y', strtotime($dc['entryTimestamp'])); ?></td>
            <td align="right">&euro; <?php echo $dc['price']; ?></td>
            <td><?php echo $dc['accountName']; ?></td>
            <td><?php echo $dc['account']; ?></td>
            <td><?php echo $dc['ledgerid']; ?></td>
            <td align="right">&euro; <?php echo $dc['extax']; ?></td>
            <td align="right">&euro; <?php echo $dc['inctax']; ?></td>
        </tr>
    <?php } ?>
</table>

<h2>Bank statement</h2>
<div>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-3">D/C</th>
            <th class="column-25">Description</th>
            <th class="column-5">Entrytime</th>
            
            <th class="column-5">Price</th>
            <th class="column-10">Account</th>
            <th class="column-10">Account Nr</th>
           
            <th class="column-10">Ledger</th>
            <th class="column-5">Extax</th>
            <th class="column-5">Inctax</th>
        </tr>
        <?php foreach ($debitcredit as $index => $dc) { ?>
            <tr class="<?php echo $dc['debitcredit']; ?> <?php echo $dc['debitcredit']; ?>">
                <td><a href="/?controller=invoice&action=editpurchasing&id=<?php echo $dc['id']; ?>">Edit</a></td>
                <td><?php echo $dc['id']; ?></td>
                <td><?php echo $dc['debitcredit']; ?></td>
                <td><?php echo $dc['description']; ?></td>
                <td><?php echo date('d-m-Y', strtotime($dc['entryTimestamp'])); ?></td>
                <td align="right">&euro; <?php echo $dc['price']; ?></td>
                
                <td><?php echo $dc['accountName']; ?></td>
                <td><?php echo $dc['account']; ?></td>
                
                
                <td><?php echo $dc['ledgerid']; ?></td>
                <td align="right">&euro; <?php echo $dc['extax']; ?></td>
                <td align="right">&euro; <?php echo $dc['inctax']; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>