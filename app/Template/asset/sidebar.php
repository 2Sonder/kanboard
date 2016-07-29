<div class="sidebar">
<!--    <form action="/?controller=asset&action=search" method="post">-->
<!--        Search: <input type="text" name="term" /><br />-->
<!--        <input type="submit" value="Submit" />-->
<!--    </form>-->
    <ul>
        <li>
            <?= $this->url->link(t('Clients'), 'asset', 'index', array()) ?>
        </li>
        <li>
            <?= $this->url->link(t('Domains'), 'asset', 'byservers', array()) ?>
        </li>
        <li>
            <?= $this->url->link(t('Servers'), 'asset', 'server', array()) ?>
        </li>
    </ul>
</div>