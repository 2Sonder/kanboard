    <form action="/?controller=asset&action=addserver" method="POST" />
    <?= $this->form->csrf() ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-6">Bewerken</th>
            <th class="column-3">#</th>
            <th class="column-10">Server</th>
            <th class="column-10">Ipv4</th>
            <th class="column-10">Client</th>

            <th class="column-10">SSH user</th>
            <th class="column-10">SSH password</th>
            <th class="column-10">CP url</th>
            <th class="column-10">CP user</th>
            <th class="column-10">CP password</th>
            <th></th>
        </tr>
        <?php foreach ($servers as $server): // print_r($server);?>

            <tr>
                <td>
                    <?= $this->url->link('Edit', 'asset', 'editserver', array('id' => $server['id']), false, '') ?>/<button class="confirmMessage" formaction="/?controller=asset&action=removeServer&id=<?php echo $server['id']; ?>">Remove</button>
                </td>
                <td>
                    <?= $server['id']; ?>
                </td>
                <td>
                    <?= $server['servername']; ?>
                </td>
                <td>
                    <?= $server['ipv4']; ?>
                </td>
                <td>
                    <?= $server['name']; ?>
                </td>
                <td>
                    <?= $server['sshuser']; ?>
                </td>
                <td>
                    <?= $server['sshpassword']; ?>
                </td>
                <td>
                    <?= $server['cpurl']; ?>
                </td>
                <td>
                    <?= $server['cpuser']; ?>
                </td>
                <td>
                    <?= $server['cppassword']; ?>
                </td>
                <td>
                 
                    <?php foreach ($server['credentials'] as $credential) { ?>
                        <a class="externalOpen" data-type="<?php echo $credential['type'] ?>" data-username="<?php echo $credential['user'] ?>" data-pass="<?php echo $credential['password'] ?>" href="<?php echo $credential['url'] ?>" target="_blank"><?php echo $credential['type'] ?></a>
                            <?php }
                        ?>
                </td>
            </tr>
        <?php endforeach ?>
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
    </table>
</form>