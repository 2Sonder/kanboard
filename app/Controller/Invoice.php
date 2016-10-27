<?php

namespace Kanboard\Controller;

use Dompdf\Dompdf;
use Kanboard\Core\ObjectStorage\FileStorage;

/**
 * Project controller (Settings + creation/edition)
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Invoice extends Base
{
    /**
     * List of projects
     *
     * @access public
     */
    public function generateMonthlyInvoices()
    {
        $products = [];
        foreach($this->sonderProduct->getAll() as $product)
        {
            $products[$product['id']] = $product;
        }

        $invoices = $this->sonderInvoice->getAll();
        $dates = array();

        $last = $this->sonderInvoice->getLastId();
        $settings = $this->sonderSettings->getAllByKey();

        $number = $this->sonderInvoice->getNextInvoiceNumber();
        if (!$number) {
            $number = ($settings['number']['settingvalue'] + 1);
        }

        $clients = $this->sonderClient->getAll();
        if (count($clients) > 0) {
            foreach ($clients as $client) {

                $dates[0]['start'] = date('Y-m-01', strtotime("+1 month"));
                $dates[0]['end'] = date('Y-m-t', strtotime("+1 month"));

                $cl = $this->sonderInvoice->getByPeriodAndClient($dates[0]['start'], $dates[0]['end'], $client['id']);
                if (!$cl) {


                    $tasks = $this->task->getPeriodByClient($dates[0]['start'], $dates[0]['end'], $client['id']);
                    if (count($tasks) > 0) {

                        $invoice = array();

                        $last = ($last + 1);
                        $invoice['id'] = $last;
                        $invoice['number'] = 'SO' . $number;
                        $number = $number + 1;

                        $invoice['beschrijvingtop'] = $settings['beschrijvingtop']['settingvalue'];
                        $invoice['beschrijvingbottom'] = $settings['beschrijvingbottom']['settingvalue'];

                        $invoice['sonder_client_id'] = $client['id'];
                        $invoice['status'] = 'Concept';
                        $invoice['date'] = $dates[0]['start'];
                        $invoice['dateto'] = $dates[0]['end'];

                        $this->sonderInvoice->save($invoice);
                    }
                }


                for ($i = 0; $i < 10; $i++) {
                    $dates[$i]['start'] = date('Y-m-01', strtotime("-$i month"));
                    $dates[$i]['end'] = date('Y-m-t', strtotime("-$i month"));

                    // if there is an invoice.
                    $cl = $this->sonderInvoice->getByPeriodAndClient($dates[$i]['start'], $dates[$i]['end'], $client['id']);
                    if (!$cl) {
                        //id 	number 	beschrijvingtop 	beschrijvingbottom 	sonder_client_id 	status 	date 	dateto


                        $tasks = $this->task->getPeriodByClient($dates[$i]['start'], $dates[$i]['end'], $client['id']);
                        if (count($tasks) > 0) {
                            $invoice = array();
                            $last = ($last + 1);
                            $invoice['number'] = 'SO' . $number;
                            $number = $number + 1;

                            $invoice['beschrijvingtop'] = $settings['beschrijvingtop']['settingvalue'];
                            $invoice['beschrijvingbottom'] = $settings['beschrijvingbottom']['settingvalue'];

                            $invoice['sonder_client_id'] = $client['id'];
                            $invoice['status'] = 'Concept';
                            $invoice['date'] = $dates[$i]['start'];
                            $invoice['dateto'] = $dates[$i]['end'];

                            $this->sonderInvoice->save($invoice);
                        }
                    }
                }

            }
        }

        //save invoicelines to invoice
        foreach ($this->sonderInvoice->getAllMonthlyInvoices() as $invoice) {
            $iscontract = false;
            $totalexcl = 0;

            $tasks = $this->task->getPeriodByClient($invoice['date'], $invoice['dateto'], $invoice['sonder_client_id']);
            foreach ($tasks as $task) {

                if (isset($task['sonder_contract_id']) && $task['sonder_contract_id'] > 0) {
                    $iscontract = true;
                } else {

                    $invoiceline = $this->sonderInvoiceLine->existBytaskId($task['id']);
                    if (!$invoiceline) {
                        $invoiceline = array();
                    } else {
                        $invoiceline = $invoiceline[0];
                    }

                    $invoiceline['sonder_invoice_id'] = $invoice['id'];
                    $invoiceline['titel'] = $task['title'];
                    $invoiceline['sonder_product_id'] = $task['sonder_product_id'];

                    if(isset($task['sonder_product_id'])) {
                        $invoiceline['price'] = ($products[$task['sonder_product_id']]['price'] * $task['billable_hours']);
                    }else{
                        $invoiceline['price'] = 0;
                    }

                    $invoiceline['discount'] = 0;

                    $totalexcl += ($invoiceline['price'] - $invoiceline['discount']);



                    $invoiceline['quantity'] = (double)$task['billable_hours'];
                    $invoiceline['task_id'] = (int)$task['id'];

                    $this->sonderInvoiceLine->save($invoiceline);
                }
            }

            if(!$iscontract) {

                $invoice['totalexcl'] = $totalexcl;
                $invoice['totalinc'] = (($totalexcl / 100) * 121);
                $this->sonderInvoice->save($invoice);
            }
        }
    }

    public function generateContractInvoices()
    {

        $products = [];
        foreach($this->sonderProduct->getAll() as $product)
        {
            $products[$product['id']] = $product;
        }

        $settings = $this->sonderSettings->getAllByKey();
        $number = $this->sonderInvoice->getNextInvoiceNumber();
        if (!$number) {
            $number = ($settings['number']['settingvalue'] + 1);
        }

        $last = $this->sonderInvoice->getLastId();
        $ids = $this->sonderInvoice->getAllContractIds();
        foreach ($this->sonderContract->getAll() as $contract) {

            $totalexcl = 0;
            $invoice = array();
            $last = ($last + 1);
            $invoice['id'] = $last;

            $invoice['number'] = 'SO' . $number;
            $number = $number + 1;

            $invoice['beschrijvingtop'] = $settings['beschrijvingtop']['settingvalue'];
            $invoice['beschrijvingbottom'] = $settings['beschrijvingbottom']['settingvalue'];
            $invoice['sonder_client_id'] = $contract['sonder_client_id'];
            $invoice['status'] = 'Concept';
            $invoice['date'] = date('Y-m-d H:i:s');
            $invoice['dateto'] = date('Y-m-d H:i:s', strtotime(date("Y-m-d", time()) . " + 365 day"));
            $invoice['sonder_contract_id'] = $contract['id'];

            $invoiceline = array();
            $invoiceline['sonder_invoice_id'] = $invoice['id'];
            $invoiceline['titel'] = $contract['name'];
            $invoiceline['quantity'] = (double)$contract['uren'];
            $invoiceline['sonder_product_id'] = $contract['sonder_product_id'];


            if(isset($contract['sonder_product_id'])) {
                $invoiceline['price'] = ($products[$contract['sonder_product_id']]['price'] * $contract['uren']);
            }else{
                $invoiceline['price'] = 0;
            }

            $invoiceline['discount'] = 0;
            $invoiceline['sonder_contract_id'] = $contract['id'];



            if (!in_array($contract['id'], $ids)) {
                $this->sonderInvoiceLine->save($invoiceline);
                $this->sonderInvoice->save($invoice, false);
            }
            else
            {
                $totalexcl += ($invoiceline['price'] - $invoiceline['discount']);

                $invoice['id'] = $contract['id'];
                $invoice['totalexcl'] = $totalexcl;
                $invoice['totalinc'] = (($totalexcl / 100) * 121);
                $this->sonderInvoice->save($invoice);
            }
        }
        //   die();
    }

    public function settings()
    {

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'settings' => $this->sonderSettings->getAllByKey()
            ),
            'title' => 'Finance / Settings',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/settings'
        )));
    }

    public function addSendInvoicesToBalance()
    {
        foreach ($this->sonderInvoice->getAll() as $invoice) {
            if ($invoice['status'] == 'Send') {
                //TODO: Post invoice pdf to 'purchasing'

            }
        }

    }

    public function index()
    {
        $this->generateContractInvoices();
        $this->generateMonthlyInvoices();
        $this->addSendInvoicesToBalance();

        $project_ids = $this->sonderClient->getAllIds();

        $paginator = $this->paginator
            ->setUrl('invoice', 'index')
            ->setMax(20)
            ->setOrder('name')
            ->setQuery($this->sonderClient->getQueryColumnStats($project_ids))
            ->calculate();

        $first = $this->sonderClient->getAll();

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => $paginator,
                'nb_projects' => 'project',
                'invoices' => $this->sonderInvoice->getAllWithClients()
            ),
            'title' => 'Invoices',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/index'
        )));
    }

    private function getBankFile($contents)
    {
        $parser = new \Kingsquare\Parser\Banking\Mt940();
        //    echo __DIR__ . '/test.mta';
        //    $tmpFile = __DIR__ . '/test.mta';
        return $parser->parse($contents);
    }

    private function saveBankFile($contents)
    {


        foreach ($this->getBankFile($contents) as $day) {
            foreach ($day->getTransactions() as $index => $t) {

                $id = $this->sonderDebitcredit->getByBankId($t->getEntryTimestamp() . $t->getTransactionCode());
                if (!$id) {
                    $cd['bankid'] = $t->getEntryTimestamp() . $t->getTransactionCode();
                    $cd['debitcredit'] = $t->getDebitcredit();
                    $cd['description'] = $t->getDescription();
                    $cd['entryTimestamp'] = date('Y-m-d H:i:s', $t->getEntryTimestamp());
                    $cd['price'] = $t->getPrice();
                    $cd['accountName'] = $t->getAccountName();
                    $cd['account'] = $t->getAccount();
                    $cd['invoice'] = '';
                    $cd['ledgerid'] = 0;
                    $cd['extax'] = 0.0;
                    $cd['inctax'] = 0.0;
                    $this->sonderDebitcredit->save($cd);
                }
            }
        }
        return true;
    }

    public function savefile()
    {
        $contents = file_get_contents($_FILES['fileToUpload']['tmp_name']);
        if ($this->saveBankFile($contents)) {
            $this->response->redirect($this->helper->url->to('invoice', 'purchasing', array()));
        }
    }

    public function purchasing()
    {
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'debitcredit' => $this->sonderDebitcredit->getAllFromBankImport(),
                'openstatements' => $this->sonderDebitcredit->getAllFromSystemImport(),
                'dc' => array('DC' => 'DC', 'C' => 'C', 'D' => 'D')
            ),
            'title' => 'Finance / Purchasing',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/purchasing'
        )));
    }

    public function savepurchasing()
    {
        $values = $this->request->getValues();
        $errors = array();

        $this->sonderDebitcredit->save($values);
        $this->response->redirect($this->helper->url->to('invoice', 'purchasing', array()));
    }

    public function editpurchasing()
    {

        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
        }

        $users = [];
        $users[0] = 'shared';
        foreach($this->user->getSelectList('id', 'name') as $user)
        {
            $users[] = $user;
        }

        $debitcredit = $this->sonderDebitcredit->getById($id);
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'debitcredit' => $debitcredit,
                'users' => $users,
                'ledgers' => $this->sonderLedger->getSelectList('id', 'name'),
                'errors' => array()
            ),
            'title' => 'Finance / Purchasing',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/editpurchasing'
        )));
    }

    public function ledger()
    {
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'ledger' => $this->sonderLedger->getAll(),
                'products' => $this->sonderProduct->getAll()
            ),
            'title' => 'Finance / Ledger',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/ledger'
        )));
    }

    public function saveInvoice($values, $invoice, $client)
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
            $invoice['department'] = $client['department'];
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

    public function fillPdfTemplate($client, $invoice, $lines)
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
            <table>
                <tr>
                    <td align="top"> <img style="width:150px;" src="' . $_SERVER["DOCUMENT_ROOT"] . '/logo.jpg" />
                            <br /><br /><br /></td>
                    <td></td>
                    <td></td>
                    <td></td>
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
            </table>    
            <table>
            <tr>
                <td  style="vertical-align: bottom;">
                    <br /><br /><br />
                    <table>
                        <tr>
                            <td>' . $invoice['name'] . '<br />
                                ' . $invoice['contact'] . '<br />
                                ' . $invoice['adres'] . '<br /> 
                                ' . $invoice['postcode'] . ' ' . $invoice['city'] . '<br /> ';

        if (isset($invoice['department']) && strlen($invoice['department']) > 0) {
            $pdf .= 'TAV ' . $invoice['department'] . '<br />';
        }

        $pdf .= '
                            </td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td></td>
            </tr>
             </table>
            <table>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>Factuurnummer: ' . $invoice['number'] . '<br />
                                Factuurdatum: ' . date('d-m-Y') . '</td>
                            <td>Relatienummer: ' . $client['number'] . '<br />
                                Vervaldatum: ' . $duedate . '
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </td>
                <td></td>
                <td></td>
            </tr>
             </table>';
        if (strlen($invoice['beschrijvingtop']) > 2) {
            $pdf .= '<br /><br /><br />
                <table>
                    <tr>
                        <td>' . $invoice['beschrijvingtop'] . '</td>
                    </tr>
                </table>
                ';
        }
        $pdf .= '
        <br /><br /><br />            
        <table>
                <tr>
                <td colspan="3">
                    <table style="width:100%;">
                        <tr>
                            <th></th>
                            <th align="left">Omschrijving</th>
                            <th align="right">Bedrag</th>
                            <th align="right">Totaal</th>  
                        </tr>';
        $next = false;
        foreach ($lines as $index => $line) {
            $product = $this->sonderProduct->getById($line['sonder_product_id']);
            $linetotal = (floatval($product['price']) * floatval($line['quantity']));
            $pdf .= '<tr>
                            <td>' . number_format((float)$line['quantity'], 1, ',', '') . ' X </td>
                            <td><b>' . ucfirst($product['title']) . '</b><br />' . ucfirst($line['titel']) . '</td>
                            <td align="right">EUR ' . number_format((float)$product['price'], 2, ',', '') . '</td>
                            <td align="right">EUR ' . number_format((float)$linetotal, 2, ',', '') . '</td>
                        </tr><tr><td colspan="4"><hr /></td></tr>';
            $invoicetotal += $linetotal;

            if ($index > 2 && $next == false) {
                $pdf .= '<tr>
        <td colspan="4">zie volgende pagina (1 van 2)</td>
</tr></table><br /><br /><table>';
                $next = true;
            }
        }

        //total
        $pdf .= '
                        <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Subtotaal</td>
                            <td align="right">EUR ' . number_format((float)$invoicetotal, 2, ',', '') . '</td>
                        </tr>';

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
                         <tr>
                            <td></td>
                            <td></td>
                            <td align="right">21% BTW</td>
                            <td align="right">EUR ' . number_format((float)$btw, 2, ',', '') . '</td>
                        </tr>
                         <tr>
                            <td></td>
                            <td></td>
                            <td align="right">Totaal</td>
                            <td align="right">EUR ' . number_format((float)$invoicetotalinc, 2, ',', '') . '</td>
                        </tr>
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

        $pdf .= '    <br /><br /><br />
                    Wil je de ' . number_format((float)$invoicetotalinc, 2, ',', '') . ' voor ' . $duedate . ' aan ons overmaken met het factuurnummer ' . $invoice['number'] . ' erbij ?<br /> Ons rekeningnummer is NL65 RABO 0303 5495 21.<br />
                    Bel gerust: 06-41844518 of email bart@2sonder.com bij vragen.<br />
                </td>
            </tr>
        </table>';

        return $pdf;
    }

    public function showpdf()
    {
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
        $invoice = $this->saveInvoice($values, $invoice, $client);

        if ($values['save'] == 'Save') {
            $this->response->redirect($this->helper->url->to('invoice', 'index', array()));
            return true;
        }

        $pdf = $this->fillPdfTemplate($client, $invoice, $lines);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $dompdf->loadHtml($pdf);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream();
    }

    public function getpdf()
    {

        $id = 1;

        $invoicetotal = 0;
        $lines = array();
        foreach ($this->task->getAll() as $task) {
            $hourlyrate = 30;

            $total = (intval($task['billable_hours']) * $hourlyrate);
            $invoicetotal += $total;

            $lines[] = array(
                'product' => 'regular client - hourly (30)',
                'description' => $task['title'],
                'price' => $hourlyrate,
                'quantity' => $task['billable_hours'],
                'discount' => 0,
                'total' => $total
            );
        }
        $btw = ($invoicetotal / 100) * 21;
        $invoicetotalinc = $btw + $invoicetotal;
        $duedate = date('d-m-Y', strtotime("+30 days"));
        $number = 'SO1111133';
        $relationumber = '18';

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => "",
                'lines' => $lines,
                'client' => $this->sonderClient->getById($id),
                'subtotal' => $invoicetotal,
                'btw' => $btw,
                'total' => $invoicetotalinc,
                'duedate' => $duedate,
                'invoicenumber' => $number,
                'relationumber' => $relationumber
            ),
            'title' => 'Invoices',
            'sub_template' => 'invoice/pdf'
        )));
    }

    public function key()
    {

        $products = array();
        foreach($this->sonderProduct->getAll() as $product)
        {
            $products[$product['id']] = $product;
        }


        $headers = array();
        $headers[] = 'Month';
        $headers[] = 'Shared';

        $users = array();
        foreach ($this->user->getAllAdmins() as $user) {
            $users[$user['id']] = $user;
            $headers[] = $user['name'];
        }

        //loop trhough bussiness months
        $months = array();
        $start = new \DateTime("2016-01-01");
        $now = new \DateTime();
        $diff = $start->diff($now)->m + ($start->diff($now)->y * 12); // int(8)
        for ($i = 0; $i < $diff; $i++) {

            if ($i > 0) {
                $start->add(new \DateInterval('P1M'));
            }


            $sharedtotal = 0;

            $month = array();
            $month['month'] = $start->format('Y-m');

            // Debit & Credit overview shared costs
            // echo $start->format('Y-m').'<br />';

            foreach($this->sonderDebitcredit->getByUserAndMonth(0,$start->format('Y-m')) as $debitcredit)
            {
                if($debitcredit['debitcredit'] == 'D') {
                    $sharedtotal += (double)$debitcredit['price'];
                }
            }

            $usrs = array();

            //loop trhough active employees & partners
            foreach($users as $user)
            {
                $usr = [];
            //    echo 'userid:'.$user['id'].'<br />';

                //calculate total billable hours & invested hours
                $tasks = $this->task->getByUserAndMonth($user['id'],$start->format('Y-m'));
                if(count($tasks)>0)
                {
                    $rates = [];
                    foreach($tasks as $task)
                    {
                        foreach($this->sonderBillablehours->getByTaskId($task['id']) as $billable)
                        {
                            if ($billable['user_id'] == $user['id'])
                            {
                                if(!isset($rates[$billable['sonder_product_id']]['value']))
                                {
                                    $rates[$billable['sonder_product_id']]['value'] = 0;
                                    $rates[$billable['sonder_product_id']]['key'] = 0;
                                }

                                $rates[$billable['sonder_product_id']]['value'] += $billable['hours'];
                                if(isset($products[$task['sonder_product_id']])) {
                                    $rates[$billable['sonder_product_id']]['key'] = $products[$task['sonder_product_id']]['title'];
                                }
                            }
                        }

                        $usr['billablehours'] = $rates;
                        $usr['investedhours'] = 0;
                        foreach($this->sonderInvestedhours->getByTaskId($task['id']) as $invested)
                        {
                            if ($invested['user_id'] == $user['id'])
                            {
                                $usr['investedhours'] += $invested['hours'];
                            }
                        }
                    }
                }

                $usr['debit'] = 0;
                foreach($this->sonderDebitcredit->getByUserAndMonth($user['id'],$start->format('Y-m')) as $debitcredit)
                {
                    if($debitcredit['debitcredit'] == 'D') {
                        $usr['debit'] += $debitcredit['price'];
                    }
                }
                $usrs[] = $usr;
            }

            $month['sharedtotal'] = $sharedtotal;
            $month['users'] = $usrs;

            $months[$start->format('Y-m')] = $month;


        }


        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'months' => $months,
                'headers' => $headers
            ),
            'title' => 'Finance / Settings',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/key'
        )));
    }

    public function saveproduct()
    {
        if (isset($_POST['title'])) {
            $values = $this->request->getValues();

            $product = array('title' => $values['title'], 'price' => $values['price']);
            $this->sonderProduct->save($product);

            $this->response->redirect($this->helper->url->to('invoice', 'ledger', array()));
        }

        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
        }

        $product = $this->sonderProduct->getById($id);
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'product' => $product[0]
            ),
            'title' => 'Finance / Ledger / Save product',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/saveproduct'
        )));
    }

    public function saveacquisition()
    {

        if (isset($_POST['name'])) {

            $values = $this->request->getValues();

            $ledger = array('name' => $values['name']);
            $this->sonderLedger->save($ledger);
            $this->response->redirect($this->helper->url->to('invoice', 'ledger', array()));
        }

        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
        }

        $ledger = $this->sonderLedger->getById($id);
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'category' => $ledger[0]
            ),
            'title' => 'Finance / Ledger / Save category',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/savecategory'
        )));
    }

    public function exchangeShortcodes($shortcodes, $text)
    {
        $t = $text;
        foreach (array_keys($shortcodes) as $shortcode) {
            $t = str_replace('[' . $shortcode . ']', $shortcodes[$shortcode], $t);
        }
        return $t;
    }

    public function newinvoice()
    {
        if (isset($_GET['id'])) {
            $invoice = $this->sonderInvoice->getById($_GET['id']);
            $lines = $this->sonderInvoiceLine->getByInvoiceId($invoice['id']);

            foreach ($lines as $index => $line) {

                $lines[$index]['description'] = $lines[$index]['titel'];

                $product = $this->sonderProduct->getById($lines[$index]['sonder_product_id']);

                if (isset($product)) {
                    $lines[$index]['product'] = $product;
                } else {
                    $lines[$index]['product'] = array('price' => 0, 'title' => '');
                }

                $lines[$index]['price'] = $lines[$index]['product']['price'];
                $lines[$index]['total'] = floatval($lines[$index]['price']) * floatval($lines[$index]['quantity']);

                if ($lines[$index]['quantity'] == 0) {
                    unset($lines[$index]);
                }
            }

            $shortcodes = array();
            $client = $this->sonderClient->getById($invoice['sonder_client_id']);

            $shortcodes['relatie'] = $client['contact'];
            $shortcodes['maand'] = date('m-Y', strtotime($invoice['date']));

            $invoice['beschrijvingtop'] = $this->exchangeShortcodes($shortcodes, $invoice['beschrijvingtop']);
            $invoice['beschrijvingbottom'] = $this->exchangeShortcodes($shortcodes, $invoice['beschrijvingbottom']);
        } else {
            $lines = array();
            $invoice = array();
        }


        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'invoice' => $invoice,
                'statusoptions' => array('Concept' => 'Concept', 'Send' => 'Send', 'Payed' => 'Payed', 'Overdue' => 'Overdue'),
                'clients' => $this->sonderClient->getAll(),
                'lines' => $lines,
                'errors' => array()
            ),
            'title' => 'Finance / Invoice / New invoice',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/new',
            'invoice_id' => 'invoice_id'
        )));
    }

    /**
     * Validate and save a new task
     *
     * @access public
     */
    public function save()
    {
        $values = $this->request->getValues();
        $errors = array();


        $values['id'] = 3;
        $this->sonderClient->create($values);

        $this->response->redirect($this->helper->url->to('invoice', 'index', array()));
    }

    /**
     * Show the project information page
     *
     * @access public
     */
    public function show()
    {

        echo 'show';
        /*
          $project = $this->getProject();

          $this->response->html($this->helper->layout->project('project/show', array(
          'project' => $project,
          'stats' => $this->project->getTaskStats($project['id']),
          'title' => $project['name'],
          ))); */
    }

    /**
     * Remove a project
     *
     * @access public
     */
    public function remove()
    {
        /*
          $project = $this->getProject();

          if ($this->request->getStringParam('remove') === 'yes') {
          $this->checkCSRFParam();

          if ($this->project->remove($project['id'])) {
          $this->flash->success(t('Project removed successfully.'));
          } else {
          $this->flash->failure(t('Unable to remove this project.'));
          }

          $this->response->redirect($this->helper->url->to('project', 'index'));
          }

          $this->response->html($this->helper->layout->project('project/remove', array(
          'project' => $project,
          'title' => t('Remove project')
          )));
         *
         */
    }

    public function routeregistration()
    {
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'routes' => $this->sonderRoute->getAllWithusersAndClients(),
                'clients' => $this->sonderClient->getList(),
                'users' => $this->user->prepareList($this->user->getAll())
            ),
            'title' => 'Finance / Invoice / Routeregistration',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/routeregistration',
            'invoice_id' => 'invoice_id'
        )));
    }

    public function saveRoute()
    {
        $values = $this->request->getValues();
        $errors = array();
        $this->sonderRoute->save($values);
        $this->response->redirect($this->helper->url->to('invoice', 'routeregistration', array()));
    }

    public function editroute()
    {

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'routes' => $this->sonderRoute->getAllWithusersAndClients(),
                'clients' => $this->sonderClient->getList(),
                'users' => $this->user->prepareList($this->user->getAll()),
                'values' => $this->sonderRoute->getById($_GET['id'])
            ),
            'title' => 'Finance / Invoice / Edit route',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/editroute',
            'invoice_id' => 'invoice_id'
        )));
    }


    public function deletecontract()
    {

    }

    public function editcontract()
    {
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'products' => $this->sonderProduct->getList(),
                'contract' => $this->sonderContract->getById($_GET['id']),
                'clients' => $this->sonderClient->getList(),
                'errors' => array()
            ),
            'title' => 'Finance / Invoice / Contracts',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/editcontract',
            'invoice_id' => 'invoice_id'
        )));
    }

    public function contract()
    {
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'products' => $this->sonderProduct->getList(),
                'contracts' => $this->sonderContract->getAll(),
                'clients' => $this->sonderClient->getList(),
                'errors' => array()
            ),
            'title' => 'Finance / Invoice / Contracts',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/contract',
            'invoice_id' => 'invoice_id'
        )));
    }

    public function savecontract()
    {
        $values = $this->request->getValues();
        $errors = array();

        $this->sonderContract->save($values);

        $this->response->redirect($this->helper->url->to('invoice', 'contract', array()));

    }
}
