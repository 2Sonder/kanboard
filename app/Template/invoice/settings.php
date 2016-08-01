<div class="page-header">
    <h2><?= t('Settings') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('client', 'save', array()) ?>" autocomplete="off">
    <?= $this->form->csrf() ?>
    <div>
        <div>
            <div>
                Beschrijving top
            </div>
            <textarea name="beschrijvingtop">
                <?php if(!isset($settings['beschrijvingtop']['settingvalue'])){ echo $settings['beschrijvingtop']['settingvalue']; }  ?>
            </textarea>
        </div>
        <div>
            <div>
                Beschrijving bottom
            </div>
            <textarea name="beschrijvingbottom">
                <?php if(!isset($settings['beschrijvingbottom']['settingvalue'])){ echo $settings['beschrijvingbottom']['settingvalue']; } ?>
            </textarea>
        </div>
        <div>
            <div>
                Latest invoice number
            </div>
            <input type="text" value="<?php if(!isset($settings['number']['settingvalue'])){  echo $settings['number']['settingvalue']; } ?>" />
        </div>
        <input type="submit" value="Save"/>
    </div>
</form>    