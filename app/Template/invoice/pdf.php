<table>
    <tr>
        <td>
            <img src="http://2sonder.com/wp-content/themes/worker/img/logo.svg" />
        </td>
        <td></td>
        <td>
            <table>
                <tr><td>Adres:</td><td>Sonder</td></tr>
                <tr><td></td><td>Kuypersweg 20</td></tr>
                <tr><td></td><td>6871ED Renkum</td></tr>
                <tr><td>KVK Nummer:</td><td>63237490</td></tr>
                <tr><td>BTW Nummer:</td><td>NL855150038B01</td></tr>
                <tr><td>Bankrekening:</td><td>NL65 RABO 0303 5495 21</td></tr>
                <tr><td>Tel:</td><td>06-41833518</td></tr>
                <tr><td>Email:</td><td>info@2sonder.com</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Taketwo<br />
                        Ronald van Dinteren<br />
                        Meester Franckenstraat 80<br />
                        6522 AH Nijmegen</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Factuurnummer: <?php echo $invoicenumber; ?><br />
                        Factuurdatum: <?php echo date('d-m-Y'); ?></td>
                    <td>Relatienummer: <?php echo $relationumber; ?><br />
                        Vervaldatum: <?php echo $duedate; ?>
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3">
            <table>
                <tr>
                    <th></th>
                    <th>Omschrijving</th>
                    <th>Bedrag</th>
                    <th>Totaal</th>  
                </tr>
                <?php foreach($lines as $line){ ?>
                <tr>
                    <td><?php echo $line['quantity']; ?></td>
                    <td><b><?php echo $line['product']; ?></b><br /><?php echo $line['description']; ?></td>
                    <td><?php echo $line['price']; ?></td>
                    <td><?php echo $line['total']; ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Subtotaal</td>
                    <td><?php echo $subtotal; ?></td>
                    
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td>21% BTW</td>
                    <td><?php echo $btw; ?></td>
                    
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td>Totaal</td>
                    <td><?php echo $total; ?></td>
                    
                </tr>
            </table>
        </td> 
    </tr>
    <tr>
        <td>
            Wil je de <?php echo $total; ?> voor <?php echo $duedate; ?> aan ons overmaken? Ons rekeningnummer is NL22RABO0132921766. Denk je eraan factuurnummer <?php echo $invoicenumber; ?> erbij te zetten?<br />
            Misschien heb je vragen of opmerkingen. Bel gerust: 06-41844518. Mailen mag natuurlijk ook: bart@2sonder.com<br />
        </td>
    </tr>
</table>

<?php
$pdf = '
<table>
    <tr>
        <td>
            <img src="http://2sonder.com/wp-content/themes/worker/img/logo.svg" />
        </td>
        <td></td>
        <td>
            <table>
                <tr><td>Adres:</td><td>Sonder</td></tr>
                <tr><td></td><td>Kuypersweg 20</td></tr>
                <tr><td></td><td>6871ED Renkum</td></tr>
                <tr><td>KVK Nummer:</td><td>63237490</td></tr>
                <tr><td>BTW Nummer:</td><td>NL855150038B01</td></tr>
                <tr><td>Bankrekening:</td><td>NL65 RABO 0303 5495 21</td></tr>
                <tr><td>Tel:</td><td>06-41833518</td></tr>
                <tr><td>Email:</td><td>info@2sonder.com</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Taketwo<br />
                        Ronald van Dinteren<br />
                        Meester Franckenstraat 80<br />
                        6522 AH Nijmegen</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td>
            <table>
                <tr>
                    <td>Factuurnummer: '.$invoicenumber.'<br />
                        Factuurdatum: '.date('d-m-Y').'</td>
                    <td>Relatienummer: '.$relationumber.'<br />
                        Vervaldatum: '.$duedate.'
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td colspan="3">
            <table>
                <tr>
                    <th></th>
                    <th>Omschrijving</th>
                    <th>Bedrag</th>
                    <th>Totaal</th>  
                </tr>
                <?php foreach($lines as $line){ ?>
                <tr>
                    <td>'.$line['quantity'].'</td>
                    <td><b>'.$line['product'].'</b><br />'.$line['description'].'</td>
                    <td>'.$line['price'].'</td>
                    <td>'.$line['total'].'</td>
                </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Subtotaal</td>
                    <td>'.$subtotal.'</td>
                    
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td>21% BTW</td>
                    <td>'.$btw.'</td>
                    
                </tr>
                 <tr>
                    <td></td>
                    <td></td>
                    <td>Totaal</td>
                    <td>'.$total.'</td>
                    
                </tr>
            </table>
        </td> 
    </tr>
    <tr>
        <td>
            Wil je de '.$total.' voor '.$duedate.' aan ons overmaken? Ons rekeningnummer is NL22RABO0132921766. Denk je eraan factuurnummer '.$invoicenumber.' erbij te zetten?<br />
            Misschien heb je vragen of opmerkingen. Bel gerust: 06-41844518. Mailen mag natuurlijk ook: bart@2sonder.com<br />
        </td>
    </tr>
</table>'; ?>