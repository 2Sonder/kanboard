
<h2>Upload transaction log</h2>
<div>
    <form class="popover-form" action="/?controller=invoice&action=savefile" enctype="multipart/form-data" method="POST" autocomplete="off">
    <?= $this->form->csrf() ?>
        Upload MT940: <input class="btn" type="file" name="fileToUpload" id="fileToUpload" />
    <input class="btn btn-info" type="submit" value="Upload bestand" />
    </form>
    <br />
</div>

<h2>Bank statement</h2>
<div>
    <form action="/?controller=credentials&action=save" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-3"></th>
            <th class="column-25">Description</th>
            <th class="column-10">EntryTimestamp</th>
            
            <th class="column-10">Price</th>
            <th class="column-15">AccountName</th>
            <th class="column-10">Account</th>
           
            <th class="column-10">Ledgerid</th>
            <th class="column-10">Extax</th>
            <th class="column-10">Inctax</th>
        </tr>
        <?php foreach ($debitcredit as $index => $dc) { ?>
            <tr class=" <?php
            if ($dc['debitcredit'] == "C") {
                echo 'By';
            } else {
                echo 'WD';
            } ?>">
                <td><a href="">Edit</a></td>
                <td><?php echo $dc['id']; ?></td>
                <td>
                    <?php
                    if ($dc['debitcredit'] == "C") {
                        echo 'By';
                    } else {
                        echo 'WD';
                    }
                    ?>
                </td>
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
       <!-- <tr>
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
        </tr>    -->
    </table>
</form>
</div>