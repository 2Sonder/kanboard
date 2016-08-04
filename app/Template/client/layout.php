<section id="main">
    <section class="sidebar-container">
        <?= $this->render($sidebar_template, array()) ?>
        <div class="sidebar-content">
            <?= $this->render($sub_template, $data) ?>
        </div>
    </section>
</section>
