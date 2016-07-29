<!--<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Clients'), 'client', 'index') ?> 
</li>-->
<?php if($this->user->isAdmin()){ ?>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Finance'), 'invoice', 'index') ?> 
</li>
<?php } ?>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Assets'), 'asset', 'index') ?> 
</li>