<div class="sidebar">
    <ul>
        <li <?= $this->app->checkMenuSelection('app', 'index') ?>>
            <?= $this->url->link(t('Provided services'), 'app', 'services', array('user_id' => $user['id'])) ?>
        </li>
 

        <?php if($this->app->checkProjectCount()){ ?>
        <li <?= $this->app->checkMenuSelection('app', 'index') ?>>
            <?= $this->url->link(t('Overview'), 'app', 'index', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('app', 'projects') ?>>
            <?= $this->url->link(t('My projects'), 'app', 'projects', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('app', 'tasks') ?>>
            <?= $this->url->link(t('My tasks'), 'app', 'tasks', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('app', 'subtasks') ?>>
            <?= $this->url->link(t('My subtasks'), 'app', 'subtasks', array('user_id' => $user['id'])) ?>
        </li>
        <?php } ?>
        <li <?= $this->app->checkMenuSelection('app', 'calendar') ?>>
            <?= $this->url->link(t('My calendar'), 'app', 'calendar', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('app', 'activity') ?>>
            <?= $this->url->link(t('My activity stream'), 'app', 'activity', array('user_id' => $user['id'])) ?>
        </li>
        <li <?= $this->app->checkMenuSelection('app', 'notifications') ?>>
            <?= $this->url->link(t('My notifications'), 'app', 'notifications', array('user_id' => $user['id'])) ?>
        </li>
        <?= $this->hook->render('template:dashboard:sidebar') ?>
    </ul>
</div>