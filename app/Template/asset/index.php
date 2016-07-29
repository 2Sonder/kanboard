
            <?= $this->url->link(t('New Client'), 'client', 'newclient', array()) ?>

    <form action="/?controller=asset&action=addclient" method="POST" />
    <table class="table-fixed table-small">
        <tr>
            <th class="column-10">Bewerken</th>
            <th class="column-5">#</th>
            <th class="column-20">Name</th>
            <th class="column-25">Email</th>
            <th class="column-20">Administrative email</th>
            <th class="column-20">Technical email</th>

            <th class="column-25">Description</th>
            <th class="column-20">Credentials</th>
        </tr>
        <?php foreach ($paginator->getCollection() as $project):
            ?>
            <tr>
                <td>
                    <?= $this->url->link('Edit', 'client', 'newclient', array('client_id' => $project['id']), false, '') ?> /<button class="confirmMessage" formaction="/?controller=asset&action=removeClient&id=<?php echo $project['id']; ?>">Remove</button>
                </td>
                <td>
                    <?php /* $this->url->link('#' . $project['id'], 'client', 'index', array('client_id' => $project['id']), false, 'dashboard-table-link')*/ ?>
                    <?= $project['id']; ?>
                </td>
                <td>
                    <?= $project['name']; ?>
                </td>
                <td class="dashboard-project-stats">
                    <?= $project['email']; ?>
                </td>
                <td class="dashboard-project-stats">
                    <?= $project['administrativeemail']; ?>
                </td>
                <td class="dashboard-project-stats">
                    <?= $project['technicalemail']; ?>
                </td>
                <td class="dashboard-project-stats">
                    <?= $project['description']; ?>
                </td>
                <td>
                    <?php foreach ($project['credentials'] as $credential) { ?>
                        <p><a class="externalOpen" data-type="<?php echo $credential['type'] ?>" data-username="<?php echo $credential['user'] ?>" data-pass="<?php echo $credential['password'] ?>" href="<?php echo $credential['url'] ?>" target="_blank"><?php echo $credential['type'] ?></a></p><br>
                    <?php }
                    ?>
                </td>
            </tr>

        <?php endforeach ?>

<!--        <tr>-->
<!--            <td></td>-->
<!--            <td></td>-->
<!--            <td><input type="text" name="name" /></td>-->
<!--            <td><input type="text" name="email" /></td>-->
<!--            <td><input type="text" name="administrativeemail" /></td>-->
<!--            <td><input type="text" name="technicalemail" /></td>-->
<!--            <td><input type="text" name="description" /></td>-->
<!--            <td><input type="submit" /></td>-->
<!--        </tr>-->

    </table>
    </form>
    <?= $paginator ?>
