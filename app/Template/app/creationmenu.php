<li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New client'), 'client', 'newclient', array(), false, 'popover') ?></li>
<li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New domain'), 'asset', 'editdomain', array(), false, 'popover') ?></li>
<li><i class="fa fa-plus fa-fw"></i><?= $this->url->link(t('New project'), 'ProjectCreation', 'create', array(), false, 'popover') ?></li>
   <li>
        <i class="fa fa-lock fa-fw"></i><?= $this->url->link(t('New private project'), 'ProjectCreation', 'createPrivate', array(), false, 'popover') ?>
    </li>
