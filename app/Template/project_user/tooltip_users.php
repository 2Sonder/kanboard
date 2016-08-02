<?php if (empty($users)): ?>
    <p><?= t('There is no project member.') ?></p>
<?php else: ?>
    <table>
    <?php foreach ($roles as $role => $role_name): ?>
        <?php if (isset($users[$role])): ?>
        <tr><th><?= $role_name ?></th></tr>
            <?php foreach ($users[$role] as $user_id => $user): ?>
                <tr><td>
                <?= $this->url->link($this->text->e($user), 'Projectuser', 'opens', array('user_id' => $user_id)) ?>
                </td></tr>
            <?php endforeach ?>
        <?php endif ?>
    <?php endforeach ?>
    </table>
<?php endif ?>