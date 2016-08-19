<form action="/?controller=asset&action=addserver" method="POST" />
<?= $this->form->csrf() ?>
<table class="table-fixed table-small">
    <tr>
        <?php if(false){ ?>
            <th class="column-6">Bewerken</th>
        <?php } ?>
        <th class="column-3">#</th>
        <th class="column-20">Name</th>
        <th class="column-20">Type</th>
        <th class="column-20">Url</th>
        <th class="column-20">Username</th>
        <th class="column-20">Password</th>
        <th class="column-10">Links</th>
        <th></th>
    </tr>
    <?php foreach ($servers as $server): // print_r($server);?>
        <tr>
            <?php foreach ($server['credentials'] as $credential) {

            ?>
                <td>
                    <?php echo $credential['id'] ?>
                </td>
                <td>
                    <?php echo $server['servername'] ?>
                </td>
                <td>
                    <?php echo $credential['type'] ?>
                </td>
                <td>
                    <?php echo $credential['url'] ?>
                </td>
                <td>
                    <?php echo $credential['user'] ?>
                </td>
                <td>
                    <?php echo $credential['password'] ?>
                </td>
                <td>
                    <a class="externalOpen" data-type="<?php echo $credential['type'] ?>"
                       data-username="<?php echo $credential['user'] ?>"
                       data-pass="<?php echo $credential['password'] ?>" href="<?php echo $credential['url'] ?>"
                       target="_blank"><?php echo $credential['type'] ?></a>
                </td>
            <?php }
            ?>
        </tr>
    <?php endforeach ?>
    <?php foreach ($domains as $domain):?>
        <tr>
            <?php foreach ($domain['credentials'] as $credential) { ?>
                <td>
                    <?php echo $credential['id'] ?>
                </td>
                <td>
                    <?php echo $domain['domainname'] ?>
                </td>
                <td>
                    <?php echo $credential['type'] ?>
                </td>
                <td>
                    <?php echo $credential['url'] ?>
                </td>
                <td>
                    <?php echo $credential['user'] ?>
                </td>
                <td>
                    <?php echo $credential['password'] ?>
                </td>
                <td>
                    <a class="externalOpen" data-type="<?php echo $credential['type'] ?>"
                       data-username="<?php echo $credential['user'] ?>"
                       data-pass="<?php echo $credential['password'] ?>" href="<?php echo $credential['url'] ?>"
                       target="_blank"><?php echo $credential['type'] ?></a>
                </td>
            <?php }
            ?>
        </tr>
    <?php endforeach ?>
    <?php if($admin){ ?>
        <tr>
            <td></td>
            <td></td>
            <td><input type="text" name="servername" /></td>
            <td><input type="text" name="ipv4" /></td>
            <td>
                <select name="sonder_client_id">
                    <option value=""></option>
                    <?php foreach ($clients as $client) { ?>
                        <option value="<?= $client['id']; ?>"><?= $client['name']; ?></option>
                    <?php } ?>
                </select>
            </td>

            <td><input type="text" name="sshuser" /></td>
            <td><input type="text" name="sshpassword" /></td>

            <td><input type="text" name="cpurl" /></td>
            <td><input type="text" name="cpuser" /></td>
            <td><input type="text" name="cppassword" /></td>

            <td><input type="submit" value="Submit" /></td>
        </tr>
    <?php } ?>
</table>
</form>