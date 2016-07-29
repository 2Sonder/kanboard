<section id="main">
    <div class="page-header">
        <ul>
            <?php if ($this->user->hasAccess('ProjectCreation', 'create')): ?>
                <li>
                    <i class="fa fa-plus fa-fw"></i>
                    <?= $this->url->link(t('New project'), 'ProjectCreation', 'create', array(), false, 'popover') ?>
                </li>
            <?php endif ?>
            <?php if ($this->app->config('disable_private_project', 0) == 0): ?>
                <li>
                    <i class="fa fa-lock fa-fw"></i>
                    <?= $this->url->link(t('New private project'), 'ProjectCreation', 'createPrivate', array(), false, 'popover') ?>
                </li>
            <?php endif ?>
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
    <section class="sidebar-container">
        <?= $this->render($sidebar_template, array()) ?>
        <div class="sidebar-content">
            <?= $this->render($sub_template, $data) ?>
        </div>
    </section>
</section>
