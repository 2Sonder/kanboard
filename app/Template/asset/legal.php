<form action="/?controller=asset&action=addserver" method="POST" />
<?= $this->form->csrf() ?>
<table class="table-fixed table-small">
    <tr>
        <?php if(false){ ?>
            <th class="column-6">Bewerken</th>
        <?php } ?>
        <th class="column-3">#</th>
        <th class="column-10">Server</th>
        <th class="column-10">Ipv4</th>
        <th></th>
    </tr>
    <?php foreach ($servers as $server): ?>
        <tr>
            <?php  if($this->user->isAdmin()){ ?>
                <td>
                    <?= $this->url->link('Edit', 'asset', 'editserver', array('id' => $server['id']), false, '') ?>/<button class="confirmMessage" formaction="/?controller=asset&action=removeServer&id=<?php echo $server['id']; ?>">Remove</button>
                </td>
            <?php } ?>
            <td>
                <?= $server['id']; ?>
            </td>
            <td>
                <?= $server['servername']; ?>
            </td>
            <td>
                <?= $server['ipv4']; ?>
            </td>
        </tr>
    <?php endforeach ?>
    <?php  if($this->user->isAdmin()){ ?>
        <tr>
            <td></td>
          
            <td><input type="text" name="cpuser" /></td>
            <td><input type="text" name="cppassword" /></td>

            <td><input type="submit" value="Submit" /></td>
        </tr>
    <?php } ?>
</table>
</form>