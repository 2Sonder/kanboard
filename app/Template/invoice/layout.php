<?php if($sub_template != 'invoice/pdf'){ ?>
<section id="main">
    <section class="sidebar-container">
        <?php if(isset($sidebar_template)){ echo $this->render($sidebar_template, array()); } ?>
        <div class="sidebar-content">
            <?php if(isset($sub_template)){ echo $this->render($sub_template, $data); } ?>
        </div>
    </section>
</section>
<? } else { ?>
           <?php if(isset($sub_template)){ echo $this->render($sub_template, $data); } ?>
<?php }?>