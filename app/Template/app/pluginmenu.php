<!--<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Clients'), 'client', 'index') ?> 
</li>-->
<?php if($this->user->isAdmin()){ ?>
<hr />
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Invoices'), 'invoice', 'index') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Transactions'), 'invoice', 'purchasing') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Ledger'), 'invoice', 'ledger') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Contracts'), 'invoice', 'contract') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Quotations'), 'quotation', 'index') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Distribution key'), 'invoice', 'key') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Route registration'), 'invoice', 'routeregistration') ?>
</li>
<?php } ?>

<hr />

<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Assets'), 'asset', 'index') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Domains'), 'asset', 'byservers') ?>
</li>
<li>
    <i class="fa fa-folder fa-fw"></i>
    <?= $this->url->link(t('Servers'), 'asset', 'server') ?>
</li>
