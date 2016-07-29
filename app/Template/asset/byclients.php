<div class="sidebar-content">
    <div class="page-header">
        <h2>Assets</h2>
    </div>

    <table class="table-fixed table-small">
        <tr>
            <th class="column-5"></th>
            <th class="column-5"><?= $paginator->order('id', 'id') ?></th>
            <th class="column-3"><?= $paginator->order('name', 'name') ?></th>
            <th class="column-25"><?= $paginator->order('email', 'email') ?></th>

        </tr>
        <?php foreach ($paginator->getCollection() as $project): ?>
            <tr>
                <td>
                    <?= $this->url->link('edit', 'client', 'newclient', array('client_id' => $project['id']), false, 'dashboard-table-link') ?>
                </td>
                <td>
                    <?= $this->url->link('#' . $project['id'], 'client', 'index', array('client_id' => $project['id']), false, 'dashboard-table-link') ?>
                </td>
                <td>
                    <?= $project['name']; ?>
                </td>
                <td class="dashboard-project-stats">
                    <?= $project['email']; ?>
                </td>
            </tr>

        <?php endforeach ?>
    </table>

    <?= $paginator ?>



</div>   