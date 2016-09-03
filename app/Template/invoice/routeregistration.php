<h2><?= t('Kilometer registration.') ?></h2>

<p>
    De regels van de belastingdienst kan je <a
        href="http://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/prive/auto_en_vervoer/u_reist_naar_uw_werk/auto_van_uw_werkgever/geen_bijtelling_bij_niet_meer_dan_500_kilometer_privegebruik/rittenregistratie/schematisch_overzicht_van_de_gegevens_voor_een_volledig_sluitende_rittenregistratie">hier</a>
    vinden.
</p>

<div>
    <form action="/?controller=invoice&action=saveacquisition" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-3">#</th>
            <th class="column-10">Name</th>
            <th class="column-5"></th>
        </tr>
        <?php foreach ($ledger as $index => $dc) { ?>
            <tr>
                <td><a href="/?controller=invoice&action=saveacquisition&id=<?php echo $dc['id']; ?>">edit</a></td>
                <td><?php echo $dc['id']; ?></td>
                <td><?php echo $dc['name']; ?></td>
                <td></td>
            </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td></td>
            <td><input type="text" name="name" value=""  /></td>
            <td>
                <input type="hidden" name="sonder_entity_name" value="sonder_client" />
                <input type="hidden" name="sonder_entity_id" value="<?php echo $client['id']; ?>" />
                <input type="submit" class="btn btn-info" value="Add category" />
            </td>
        </tr>
    </table>
    </form>
</div>