<h2><?= t('Kilometer registration.') ?></h2>

<p>
    De regels van de belastingdienst kan je <a
        href="http://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/prive/auto_en_vervoer/u_reist_naar_uw_werk/auto_van_uw_werkgever/geen_bijtelling_bij_niet_meer_dan_500_kilometer_privegebruik/rittenregistratie/schematisch_overzicht_van_de_gegevens_voor_een_volledig_sluitende_rittenregistratie">hier</a>
    vinden.
</p>

<div>
    <?
    /*
     id
	2	user_id
	3	date
	4	sonder_client_id
	5	adres
	6	postcode
	7	plaats
	8	adres2
	9	postcode2
	10	plaats2
	11	km
     * */
    ?>

    <form action="/?controller=invoice&action=saveroute" method="POST" />
    <?= $this->form->csrf(); ?>
    <table class="table-fixed table-small">
        <tr>
            <th class="column-3"></th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-5">From</th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-5">To</th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-10"></th>
            <th class="column-3"></th>
        </tr>
        <tr>
            <th class="column-3">#</th>
            <th class="column-10">User</th>
            <th class="column-10">Date</th>
            <th class="column-10">Client</th>
            <th class="column-5">Postcode</th>
            <th class="column-10">Adres</th>
            <th class="column-10">Plaats</th>
            <th class="column-5">Postcode</th>
            <th class="column-10">Adres</th>
            <th class="column-10">Plaats</th>
            <th class="column-10">Kilometers</th>
            <th class="column-10"></th>
            <th class="column-3"></th>

        </tr>

        <?php foreach ($routes as $index => $route) { ?>
            <tr>
                <td><?php echo $route['id']; ?></td>
                <td><?php echo $route['user_id']; ?></td>

                <td><?php echo $route['date']; ?></td>
                <td><?php echo $route['sonder_client_id']; ?></td>
                <td><?php echo $route['postcode']; ?></td>
                <td><?php echo $route['adres']; ?></td>

                <td><?php echo $route['plaats']; ?></td>
                <td><?php echo $route['postcode2']; ?></td>
                <td><?php echo $route['adres2']; ?></td>
                <td><?php echo $route['plaats2']; ?></td>
                <td><?php echo $route['km']; ?></td>
                <td></td>
            </tr>
        <?php } ?>
        <tr>
            <td></td>
            <td><?= $this->form->select('user_id', $users , array(), array(), array('required'), 'form-input-large'); ?></td>
            <td><?= $this->helper->form->text('date', array(), array(), array(), 'form-date') ?></td>
            <td><?= $this->form->select('sonder_client_id', $clients , array(), array(), array('required'), 'form-input-large'); ?></td>
            <td><input type="text" value="" name="adres" /></td>
            <td><input type="text" value="" name="postcode" /></td>
            <td><input type="text" value="" name="plaats" /></td>
            <td><input type="text" value="" name="adres2" /></td>
            <td><input type="text" value="" name="postcode2" /></td>
            <td><input type="text" value="" name="plaats2" /></td>
            <td><input type="text" value="" name="km" /></td>
            <td>
                <input type="submit" class="btn btn-info" value="Add category" />
            </td>
        </tr>
    </table>
    </form>
</div>