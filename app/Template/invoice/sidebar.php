<div class="sidebar">
    <ul>
        <li>
            <?= $this->url->link(t('Purchasing'), 'invoice', 'purchasing', array()) ?>
        </li>
        <li>
            <?= $this->url->link(t('Ledger'), 'invoice', 'ledger', array()) ?>
        </li>
        <li>
            <?= $this->url->link(t('Invoices'), 'invoice', 'index', array()) ?>
        </li>        
        <li>
            <?= $this->url->link(t('Distribution key'), 'invoice', 'key', array()) ?>
        </li>
        <li>
            <?= $this->url->link(t('Kilometer registration'), 'invoice', 'routeregistration', array()) ?>
        </li>
    </ul>
</div>