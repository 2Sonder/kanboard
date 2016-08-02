<div style="background-color: #414f57;padding:10px;">
    <a style="padding:10px;margin:10px;color: #2a62bc;font-size: 16px;" href="https://docs.google.com/spreadsheets/d/1ATgh7q7dlvnAIxJB8OLWsq7SW8RfggiEin8ctvllHhM/edit#gid=0">report hier je bugs</a>
</div>
<header>
    <nav>
        <h1>
            <span class="logo">
                <?= $this->url->link('<i class="fa fa-home fa-fw"></i>', 'app', 'index', array(), false, '', t('Dashboard')) ?>
            </span>
            <span class="title">
                <?= $this->text->e($title) ?>
            </span>
            <?php if (!empty($description)): ?>
                <span class="tooltip" title="<?= $this->text->markdownAttribute($description) ?>">
                    <i class="fa fa-info-circle"></i>
                </span>
            <?php endif ?>
        </h1>
        <ul class="search-main">
            <li>
                <form method="get" action="<?= $this->url->dir() ?>" class="search">
                    <?= $this->form->hidden('controller', array('controller' => 'search')) ?>
                    <?= $this->form->hidden('action', array('action' => 'index')) ?>
                    <input id="main-search" type="text" name="search" placeholder="Zoeken in PM" />
                    <?= $this->render('app/filters_helper') ?>
                </form>
            </li>
        </ul>
        <ul>
            <?php if (isset($board_selector) && !empty($board_selector)): ?>
                <li>
                    <select id="board-selector"
                            class="chosen-select select-auto-redirect"
                            tabindex="-1"
                            data-search-threshold="0"
                            data-notfound="<?= t('No results match:') ?>"
                            data-placeholder="<?= t('Display another project') ?>"
                            data-redirect-regex="PROJECT_ID"
                            data-redirect-url="<?= $this->url->href('board', 'show', array('project_id' => 'PROJECT_ID')) ?>">
                        <option value=""></option>
                        <?php foreach ($board_selector as $board_id => $board_name): ?>
                            <option value="<?= $board_id ?>"><?= $this->text->e($board_name) ?></option>
                        <?php endforeach ?>
                    </select>
                </li>
            <?php endif ?>
            <li class="user-links">

                <?php if ($this->user->hasNotifications()): ?>
                    <span class="notification">
                        <?= $this->url->link('<i style="padding-top:20px;" class="fa fa-bell web-notification-icon"></i>', 'app', 'notifications', array('user_id' => $this->user->getId()), false, '', t('Unread notifications')) ?>
                    </span>
                <?php endif ?>
                <?php $has_project_creation_access = $this->user->hasAccess('ProjectCreation', 'create'); ?>
                <?php $is_private_project_enabled = $this->app->config('disable_private_project', 0) == 0; ?>
                <div class="dropdown dropdown-icon">
                    <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i style="position:relative;top:7px;" class="fa fa-th fa-2x fa-fw"></i></a>
                    <ul>
                        <li>
                            <i class="fa fa-search fa-fw"></i>
                            <?= $this->url->link(t('Search'), 'search', 'index') ?>
                        </li>
                        <li>
                            <i class="fa fa-folder fa-fw"></i>
                            <?= $this->url->link(t('Project management'), 'project', 'index') ?>
                        </li>
                        <?= $this->render('app/pluginmenu'); ?>
                    </ul>
                </div>
                <?php if ($has_project_creation_access || (!$has_project_creation_access && $is_private_project_enabled)): ?>
                    <div class="dropdown dropdown-icon">
                        <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i style="position:relative;top:7px;" class="fa fa-plus fa-2x fa-fw"></i></a>
                        <ul>
                            <?php if ($has_project_creation_access): ?>
                                <li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New project'), 'ProjectCreation', 'create', array(), false, 'popover') ?></li>
                            <?php endif ?>
                            <?php if ($is_private_project_enabled): ?>
                                <li>
                                    <i class="fa fa-lock fa-fw"></i><?= $this->url->link(t('New private project'), 'ProjectCreation', 'createPrivate', array(), false, 'popover') ?>
                                </li>
                            <?php endif ?>
                        </ul>
                    </div>
                <?php endif ?>

                <div class="dropdown dropdown-avatar">
                    <a href="#" class="dropdown-menu dropdown-menu-link-icon"><?= $this->avatar->currentUserSmall('avatar-inline') ?></a>
                    <ul>
                        <li class="no-hover"><strong><?= $this->text->e($this->user->getFullname()) ?></strong></li>
                        <li>
                            <i class="fa fa-tachometer fa-fw"></i>
                            <?= $this->url->link(t('My dashboard'), 'app', 'index', array('user_id' => $this->user->getId())) ?>
                        </li>
                        <li>
                            <i class="fa fa-home fa-fw"></i>
                            <?= $this->url->link(t('My profile'), 'user', 'show', array('user_id' => $this->user->getId())) ?>
                        </li>
                        <li>
                            <i class="fa fa-folder fa-fw"></i>
                            <?= $this->url->link(t('Projects management'), 'project', 'index') ?>
                        </li>
                        <?php if ($this->user->hasAccess('user', 'index')): ?>
                            <li>
                                <i class="fa fa-user fa-fw"></i>
                                <?= $this->url->link(t('Users management'), 'user', 'index') ?>
                            </li>
                            <li>
                                <i class="fa fa-group fa-fw"></i>
                                <?= $this->url->link(t('Groups management'), 'group', 'index') ?>
                            </li>
                            <li>
                                <i class="fa fa-cog fa-fw"></i>
                                <?= $this->url->link(t('Settings'), 'config', 'index') ?>
                            </li>
                        <?php endif ?>
                        <li>
                            <i class="fa fa-life-ring fa-fw"></i>
                            <?= $this->url->link(t('Documentation'), 'doc', 'show') ?>
                        </li>
                        <?php if (!DISABLE_LOGOUT): ?>
                            <li>
                                <i class="fa fa-sign-out fa-fw"></i>
                                <?= $this->url->link(t('Logout'), 'auth', 'logout') ?>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>

            </li>
        </ul>
    </nav>
</header>
