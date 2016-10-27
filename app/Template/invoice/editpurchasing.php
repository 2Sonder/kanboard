<form action="/?controller=invoice&action=savepurchasing" method="POST"/>
    <?= $this->form->csrf(); ?>
    <?
    /*
    1 	id  Primaire sleutel 	int(11) 			Nee 	Geen 		AUTO_INCREMENT 	Veranderen Veranderen 	Verwijderen Verwijderen
	2 	bankid 	varchar(255) 	latin1_swedish_ci 		Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	3 	debitcredit 	varchar(2) 	latin1_swedish_ci 		Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	4 	description 	varchar(255) 	latin1_swedish_ci 		Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	5 	entryTimestamp 	datetime 			Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	6 	price 	double(8,2) 			Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	7 	accountName 	varchar(255) 	latin1_swedish_ci 		Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	8 	account 	varchar(45) 	latin1_swedish_ci 		Nee 	Geen 			Veranderen Veranderen 	Verwijderen Verwijderen
	9 	invoice 	varchar(255) 	latin1_swedish_ci 		Ja 	NULL 			Veranderen Veranderen 	Verwijderen Verwijderen
	10 	ledgerid 	int(11) 			Ja 	NULL 			Veranderen Veranderen 	Verwijderen Verwijderen
	11 	extax 	double(8,2) 			Ja 	NULL 			Veranderen Veranderen 	Verwijderen Verwijderen
	12 	inctax 	double(8,2) 			Ja 	NULL 			Veranderen Veranderen 	Verwijderen Verwijderen
	13 	user_id
    */
    ?>

    <table class="table-fixed table-small">
        <tr>

            <th class="column-3">#</th>
            <th class="column-3">D/C</th>
            <th class="column-25">Description</th>
            <th class="column-5">EntryTimestamp</th>

            <th class="column-5">Price</th>
            <th class="column-10">Account</th>
            <th class="column-10">Account Nr</th>

            <th class="column-10">Ledgerid</th>
            <th class="column-5">Extax</th>
            <th class="column-5">Inctax</th>
            <th class="column-10">User</th>
            <th class="column-10"></th>
        </tr>
        <tr class="">
            <td><?php echo $debitcredit['id']; ?></td>
            <td><?php echo $debitcredit['debitcredit']; ?></td>
            <td><input type="text" value="<?php echo $debitcredit['description']; ?>" name="description"></td>
            <td><?php echo date('d-m-Y', strtotime($debitcredit['entryTimestamp'])); ?></td>
            <td align="right">&euro; <?php echo $debitcredit['price']; ?></td>
            <td><?php echo $debitcredit['accountName']; ?></td>
            <td><?php echo $debitcredit['account']; ?></td>
            <td>
                <?= $this->form->select('ledgerid', $ledgers , $debitcredit, $errors, array('required'), 'form-input-large') ?>
            </td>
            <td align="right">&euro; <?php echo $debitcredit['extax']; ?></td>
            <td align="right">&euro; <?php echo $debitcredit['inctax']; ?></td>
            <td>
                <?= $this->form->select('user_id', $users , $debitcredit, $errors, array('required'), 'form-input-large') ?>
            </td>
            <td>
                <input type="hidden" name="id" value="<?php echo $debitcredit['id']; ?>"/>
        <!--        <input type="hidden" name="sonder_entity_name" value="sonder_debitcredit"/>
                <input type="hidden" name="sonder_entity_id" value="<?php echo $debitcredit['id']; ?>"/> -->
                <input type="submit" class="btn btn-info" value="Save transaction"/>
            </td>

        </tr>
    </table>
</form>