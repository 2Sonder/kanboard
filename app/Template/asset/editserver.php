<div class="sidebar-content">
    <div class="page-header">
        <h2>Domains</h2>
    </div>
    <form action="/?controller=asset&action=saveeditserver&id=<?php echo $domainid; ?>" method="POST">
        <?= $this->form->csrf(); ?>
        <table class="table-fixed table-small">
            <tr>
                <th class="column-3">id</th>
                <th class="column-10">server</th>
                <th class="column-10">ipv4</th>
                <th class="column-10">Client</th>
                <th></th>
            </tr>
            <?php $server = $server[0]; ?>
            <tr>
                <td>
                    <?php echo $server['id']; ?>
                </td>
                <td>
                    <input type="text" name="servername" value="<?= $server['servername']; ?>" />
                </td>
                <td>
                    <input type="text" name="ipv4" value="<?= $server['ipv4']; ?>" />
                </td>

                <td>
                    <select name="sonder_server_id">
                        <option value=""></option>
                        <?php foreach ($clients as $client) { ?>
                            <option <?php
                            if ($server['sonder_client_id'] == $client['id']) {
                                echo 'selected';
                            }
                            ?> value="<?= $client['id']; ?>"><?= $client['name']; ?></option>
                            <?php } ?>
                    </select>
                </td>
                <td><input type="submit" /></td>
            </tr>     
        </table>
    </form>    
    <div class="page-header">
        <h2>Credentials</h2>
    </div>
    <form action="/?controller=credentials&action=save" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-10">type</th>
            <th class="column-10">url</th>
            <th class="column-10">user</th>
            <th class="column-8">password</th>
            <th class="column-5"></th>
        </tr>
        <?php
        $serverid = $server['id'];
        foreach ($credentials as $index => $cred) {
            ?>
            <tr>
                <td><input type="text" name="type_<?php echo $index; ?>" value="<?php echo $cred['type']; ?>"  /></td>
                <td><input type="hidden" name="id_<?php echo $index; ?>" value="<?php echo $cred['id']; ?>" />
                    <input type="text" name="url_<?php echo $index; ?>" value="<?php echo $cred['url']; ?>"  /></td>
                <td><input type="text" name="user_<?php echo $index; ?>" value="<?php echo $cred['user']; ?>"  /></td>
                <td><input type="text" name="password_<?php echo $index; ?>" value="<?php echo $cred['password']; ?>"  /></td>
                <td>
                    <input type="hidden" name="sonder_entity_id" value="<?php echo $serverid; ?>" />
                    <input type="hidden" name="sonder_entity_name" value="sonder_server" />
                    <input type="submit" value="Edit" />/
                    <button formaction="/?controller=credentials&action=remove&id=<?php echo $cred['id']; ?>">Remove</button>
                </td>
            </tr>
<?php } ?>
        <tr>
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
            <input type="hidden" name="sonder_entity_name" value="sonder_server" />
            <input type="hidden" name="sonder_entity_id" value="<?php echo $serverid; ?>" /><input type="submit" value="add passwords" /></td>
        </tr>    
    </table>
</form>