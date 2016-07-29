    <form action="/?controller=asset&action=adddomain" method="POST" />
    <?= $this->form->csrf() ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-5">Bewerken</th>
            <th class="column-10">Servername</th>
            <th class="column-8">Ip</th>
            <th class="column-10">Domainname</th>
            <th class="column-10">SSL</th>
            <th class="column-10">Client</th>
            <th class="column-10">Sub-Client</th>
            <th class="column-5">Status</th>
            <th class="column-10">Credentials</th>
        </tr>

        <?php foreach ($paginator as $domain) { $pos = strpos($domain['status'],'offline'); ?>
            <tr>
                <td><a href="/?controller=asset&action=editdomain&id=<?php echo $domain['domainid']; ?>">Edit</a>/<button class="confirmMessage" formaction="/?controller=asset&action=removeDomain&id=<?php echo $domain['domainid']; ?>">Remove</button></td>
                <td><?= $domain['servername']; ?></td>
                <td><?php 
                    if(isset($domain['altip']) && strlen($domain['altip']) > 0)
                    {
                        echo $domain['altip'];
                    }
                    else
                    {
                        echo $domain['ipv4'];    
                    }
                    ?>    
                </td>
                <td>
                <?php if($pos === false){ echo "<a target='_blank' href='http://".$domain['domainname']."'>".$domain['domainname']."</a>"; }else{  echo $domain['domainname'];}  ?>
                
                </td>
                
                <td><?= $domain['ssl']; ?></td>
                <td><?= $domain['name']; ?></td>
                <td><?= $domain['name']; ?></td>
                <td <?php  if($pos === false){  }else{ echo 'style="background-color:#f00;"'; }  ?> ><?= $domain['status']; ?></td>
                <td><?php foreach ($domain['credentials'] as $credential) { ?>
                        <a class="externalOpen" data-type="<?php echo $credential['type'] ?>" data-username="<?php echo $credential['user'] ?>" data-pass="<?php echo $credential['password'] ?>" href="<?php echo $credential['url'] ?>" target="_blank"><?php echo $credential['type'] ?></a><?php } ?></td>
            </tr>    
        <?php } 
       
        ?>
        <tr>
            <td></td>
            <td>
                <select name="sonder_server_id">
                    <option value=""></option>
                    <?php foreach($servers as $server){ ?>
                    <option value="<?=$server['id']; ?>"><?=$server['servername']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td><input type="text" name="altip" /></td>
            <td><input type="text" name="domainname" /></td>
            <td><input type="text" name="ssl" /></td>
            <td>
                <select name="sonder_client_id">
                    <option value=""></option>
                    <?php foreach($clients as $client){ ?>
                    <option value="<?=$client['id']; ?>"><?=$client['name']; ?></option>
                    <?php } ?>
                </select>
            </td>
            <td></td>
            <td><input type="text" name="status" /></td>
            <td><input type="submit" /></td>
        </tr>        
    </table>
</form>