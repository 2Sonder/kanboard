<h2><?= t('Kilometer registration.') ?></h2>



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
          <tr>
            <td>
                <input type="hidden" name="id" value="<?php echo $values['id']; ?>"/> </td>
            <td><?= $this->form->select('user_id', $users , $values, array(), array('required'), 'form-input-large'); ?></td>
            <td><?= $this->helper->form->text('date', $values, array(), array(), 'form-date') ?></td>
            <td><?= $this->form->select('sonder_client_id', $clients ,$values, array(), array('required'), 'form-input-large'); ?></td>
            <td><?= $this->form->text('postcode', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('adres', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('plaats', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('postcode2', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('adres2', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('plaats2', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td><?= $this->form->text('km', $values, array(), array('autofocus', 'required', 'maxlength="200"', 'tabindex="1"'), 'form-input-large') ?>            </td>
            <td>
                <input type="submit" class="btn btn-info" value="Edit route" />
            </td>
        </tr>
    </table>
    </form>
</div><?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/25/2016
 * Time: 2:09 AM
 */