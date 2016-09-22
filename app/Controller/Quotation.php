<?php

namespace Kanboard\Controller;

use Dompdf\Dompdf;
use Kanboard\Core\ObjectStorage\FileStorage;

/**
 * Link controller
 *
 * @package controller
 * @author  Olivier Maridat
 * @author  Frederic Guillot
 */
class Quotation extends Base
{
    /**
     * List of projects
     *
     * @access public
     */
    public function index()
    {

        $this->showQuote();

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'products' => $this->sonderProduct->getList(),
                'contracts' => $this->sonderContract->getAll(),
                'clients' => $this->sonderClient->getList(),
                'errors' => array()
            ),
            'title' => 'Finance / Quotations',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'quotation/index',
            'invoice_id' => 'invoice_id'
        )));

    }

    public function showQuote()
    {

        /*
        $values = $this->request->getValues();
        $invoice = $this->sonderInvoice->getById($_GET['id']);
        //$lines = $this->sonderInvoiceLine->getByInvoiceId($invoice['id']);
        $lines = [];
        foreach ($this->sonderInvoiceLine->getByInvoiceId($invoice['id']) as $line) {
            $ls[$line['id']] = $line;
        }

        $keys = array_keys($ls);
        for ($i = 0; $i < $values['linecount']; $i++) {
            if (isset($values['id_' . $i])) {
                $lineid = $values['id_' . $i];
                if (in_array($lineid, $keys)) {
                    $lines[] = $ls[$lineid];
                }
            }
        }

        $client = $this->sonderClient->getById($invoice['sonder_client_id']);
      //  $invoice = $this->saveQuote($values, $invoice, $client);
/*
        if ($values['save'] == 'Save') {
            $this->response->redirect($this->helper->url->to('invoice', 'index', array()));
            return true;
        }*/


        $client = $this->sonderClient->getById(25);

        $invoice = array();
        $invoice['name'] = $client['name'];
        $invoice['contact'] = $client['contact'];
        $invoice['adres'] = $client['adres'];
        $invoice['postcode'] = $client['postcode'];
        $invoice['city'] = $client['city'];
        $invoice['beschrijvingtop'] = '
        Beste Bibliotheek Rivierenland,<br />
        <br />
        <br />
        We bieden een helpdesk dienst aan op werktijd telefonisch van 9:00 tot 21:00 en zaterdag van 09:00 tot 16:00. <br /> 
        Hiervoor rekenen we maandelijks 45 euro, per gesprek of per uur rekenen we 30 euro per uur.<br />
        Eenmalig rekenen we 3 X 30 euro voor een intake, kennis maken en opzetten van het communicatie protocol.<br />  
        Dit is een contract van minimaal 6 maanden, en wordt verlengt per 6 maanden. <br />';
        $invoice['beschrijvingbottom'] = '';//'beschrijvingbottom';


        $line = [];
        $line['titel'] = 'Intake kosten';
        $line['price'] = '30';
        $line['quantity'] = '3';
        $line['sonder_product_id'] = 2;
        $oncelines[] = $line;

        $line = [];
        $line['titel'] = 'Kosten ieder gemaakt uur';
        $line['price'] = '30';
        $line['quantity'] = ' N&nbsp;&nbsp;&nbsp;';
        $line['sonder_product_id'] = 2;
        $monthlylines[] = $line;
        $line['titel'] = 'Maandelijkse kosten beschikbaarheid.';
        $line['price'] = '45';
        $line['quantity'] = '1';
        $line['sonder_product_id'] = 6;
        $monthlylines[] = $line;

        $pdf = $this->fillPdfTemplate($client, $invoice, $oncelines, $monthlylines);


        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($pdf);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }


    public function saveQuote($values, $invoice, $client)
    {

        $shortcodes = array();
        $shortcodes['relatie'] = $client['contact'];
        $shortcodes['maand'] = date('m-Y', strtotime($invoice['date']));

        $invoice['beschrijvingtop'] = $values['beschrijvingtop'];
        $invoice['beschrijvingbottom'] = $values['beschrijvingbottom'];
        $invoice['number'] = $values['number'];
        $invoice['status'] = $values['status'];

        if (strlen($invoice['adres']) == 0) {
            $invoice['name'] = $client['name'];
            $invoice['contact'] = $client['contact'];
            $invoice['adres'] = $client['adres'];
            $invoice['postcode'] = $client['postcode'];
            $invoice['city'] = $client['city'];
        }

        $invoice['beschrijvingtop'] = $this->exchangeShortcodes($shortcodes, $invoice['beschrijvingtop']);
        $invoice['beschrijvingbottom'] = $this->exchangeShortcodes($shortcodes, $invoice['beschrijvingbottom']);

        $invoice['percentdiscount'] = $values['percentdiscount'];
        $invoice['discount'] = $values['discount'];

        if ($this->sonderInvoice->save($invoice)) {
            echo 'saved';
        }

        return $invoice;
    }

    public function fillPdfTemplate($client, $invoice, $lines, $monthlylines)
    {
        $duedate = date('d-m-Y', strtotime("+30 days"));

        $invoicetotal = 0;
        $pdf = '
                    <style>
                        table
                        {
                            width:100%;
                            font-family:Arial, Helvetica, sans-serif;
                            font-size:12px;
                        }
                    </style>
            <table width="100%">
                <tr>
                    <td align="top"> <img style="width:150px;" src="' . $_SERVER["DOCUMENT_ROOT"] . '/logo.jpg" />
                            <br /><br /><br /></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td><td></td><td></td><td></td><td></td>
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
            </table>    
            <table width="100%">
            <tr>
                <td  style="vertical-align: bottom;">
                    <br /><br /><br />
                </td>
                <td></td>
                <td></td>
            </tr>
             </table>';
        if (strlen($invoice['beschrijvingtop']) > 2) {
            $pdf .= '
                <table>
                    <tr>
                        <td>' . $invoice['beschrijvingtop'] . '</td>
                    </tr>
                </table>
                ';
        }
        $pdf .= '
        
        
        <table>
            <tr><td><h2>Eenmalige kosten</h2></td></tr>
        </table>
        <table>
                <tr>
                <td colspan="3">
                    <table style="width:100%;">
                        <tr>
                            <th></th>
                            <th align="left">Omschrijving</th>
                            <th align="right">Bedrag</th>
                        </tr>';
        foreach ($lines as $line) {
            $product = $this->sonderProduct->getById($line['sonder_product_id']);
            $linetotal = (floatval($product['price']) * floatval($line['quantity']));
            $pdf .= '<tr>';

            if (is_numeric($line['quantity']) && $line['quantity'] > 0) {
                $pdf .= '<td>' . number_format((float)$line['quantity'], 1, ',', '') . ' X </td>';
            } else {
                $pdf .= '<td>' . $line['quantity'] . ' X </td>';
            }

            $pdf .= '       <td><b>' . ucfirst($product['title']) . '</b><br />' . ucfirst($line['titel']) . '</td>
                            <td align="right">EUR ' . number_format((float)$product['price'], 2, ',', '') . '</td>
                        </tr><tr><td colspan="4"><hr /></td></tr>';
            $invoicetotal += $linetotal;
        }

        //discount
        if (isset($invoice['percentdiscount']) && $invoice['percentdiscount'] > 0) {

            $discount = ($invoicetotal / 100) * $invoice['percentdiscount'];
            $invoicetotal = ($invoicetotal - $discount);

            $pdf .= '
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Korting (' . $invoice['percentdiscount'] . '%)</td>
                            <td align="right">EUR -' . number_format((float)$discount, 2, ',', '') . '</td>
                        </tr>            
                    ';
        } else if (isset($invoice['discount']) && $invoice['discount'] > 0) {

            $invoicetotal = $invoicetotal - $invoice['discount'];

            $pdf .= '
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Korting</td>
                            <td align="right">EUR -' . number_format((float)$invoice['discount'], 2, ',', '') . '</td>
                        </tr>            
                    ';
        }

        $btw = ($invoicetotal / 100) * 21;
        $invoicetotalinc = $btw + $invoicetotal;

        $pdf .= '
                        
                    </table>
                </td> 
            </tr>
            </table>
             <table>
                <tr><td><br /><h2>Maandelijkse kosten</h2></td></tr> 
             </table>
                <table>
                <tr>
                <td colspan="3">
                    <table style="width:100%;">
                        <tr>
                            <th></th>
                            <th align="left">Omschrijving</th>
                            <th align="right">Bedrag</th>
                            
                        </tr>';
        foreach ($monthlylines as $line) {
            $product = $this->sonderProduct->getById($line['sonder_product_id']);
            $linetotal = (floatval($product['price']) * floatval($line['quantity']));
            $pdf .= '<tr>';

            if (is_numeric($line['quantity']) && $line['quantity'] > 0) {
                $pdf .= '<td>' . number_format((float)$line['quantity'], 1, ',', '') . ' X </td>';
            } else {
                $pdf .= '<td>' . $line['quantity'] . ' X </td>';
            }

            $pdf .= '       <td><b>' . ucfirst($product['title']) . '</b><br />' . ucfirst($line['titel']) . '</td>
                            <td align="right">EUR ' . number_format((float)$product['price'], 2, ',', '') . '</td>
                        </tr><tr><td colspan="4"><hr /></td></tr>';
            $invoicetotal += $linetotal;
        }

        //total
        $pdf .= '';

        //discount
        if (isset($invoice['percentdiscount']) && $invoice['percentdiscount'] > 0) {

            $discount = ($invoicetotal / 100) * $invoice['percentdiscount'];
            $invoicetotal = ($invoicetotal - $discount);

            $pdf .= '   <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Korting (' . $invoice['percentdiscount'] . '%)</td>
                            <td align="right">EUR -' . number_format((float)$discount, 2, ',', '') . '</td>
                        </tr>            
                    ';
        } else if (isset($invoice['discount']) && $invoice['discount'] > 0) {

            $invoicetotal = $invoicetotal - $invoice['discount'];

            $pdf .= '
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Korting</td>
                            <td align="right">EUR -' . number_format((float)$invoice['discount'], 2, ',', '') . '</td>
                        </tr>            
                    ';
        }

        $btw = ($invoicetotal / 100) * 21;
        $invoicetotalinc = $btw + $invoicetotal;

        $pdf .= '
                        
                    </table>
                </td> 
            </tr>
            </table>
            <table>
            <tr >
                <td colspan="3">';

        if (strlen($invoice['beschrijvingbottom']) > 2) {
            $pdf .= ' <b>' . $invoice['beschrijvingbottom'] . '</b>';
        }

        $pdf .= ' 
    <table><tr><td><h2>Handtekeningen</h2></td></tr></table>
            <table>
           <tr>
           <td>Sonder, Bart Breunesse<hr /><br /><br /><br /><br /></td>
           <td></td>
           <td>Bibliotheek rivierenland, <hr /><br /><br /><br /><br /></td>
</tr>
            <tr>
            
            <td>
         <hr />   
</td><td></td><td>
<hr />
</td>
</tr>
</table>
    
    <br /><br /><br />
                    Offertes zijn 30 dagen geldig, deze offerte is geldig tot ' . $duedate . '. 
                    Wanneer een offerte wordt getekend is het tevens de overeenkomst voor de te leveren diensten. 
                    Op al onze offertes en overeenkomsten zijn de <a href="http://2sonder.com/wp-content/uploads/2016/09/algemene-voorwaarden.pdf">algemene voorwaarden</a> van toepassing.
                </td>
            </tr>
        </table>';

        return $pdf;
    }
}