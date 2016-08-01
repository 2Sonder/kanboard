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
        $invoices = $this->sonderInvoice->getAll();
        $dates = array();

        $last = $this->sonderInvoice->getLastId();
        $settings = $this->sonderSettings->getAllByKey();

        $number = $this->sonderInvoice->getNextInvoiceNumber();
        if(!$number) {
            $number = ($settings['number']['settingvalue'] + 1);
        }

        $clients = $this->sonderClient->getAll();
        if (count($clients) > 0) {
            foreach ($clients as $client) {

                $dates[0]['start'] = date('Y-m-01', strtotime("+1 month"));
                $dates[0]['end'] = date('Y-m-t', strtotime("+1 month"));

                $cl = $this->sonderInvoice->getByPeriodAndClient($dates[0]['start'], $dates[0]['end'], $client['id']);
                if (!$cl) {

                    //id 	number 	beschrijvingtop 	beschrijvingbottom 	sonder_client_id 	status 	date 	dateto
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

                  //  echo 'here1<br />';

                    $cl = $this->sonderInvoice->getByPeriodAndClient($dates[$i]['start'], $dates[$i]['end'], $client['id']);
                    if (!$cl) {

                        //id 	number 	beschrijvingtop 	beschrijvingbottom 	sonder_client_id 	status 	date 	dateto 
                        $tasks = $this->task->getPeriodByClient($dates[$i]['start'], $dates[$i]['end'], $client['id']);
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
                            $invoice['date'] = $dates[$i]['start'];
                            $invoice['dateto'] = $dates[$i]['end'];

                            $this->sonderInvoice->save($invoice);
                        }
                    }
                }
            }

        }

        //save invoicelines to invoice
        foreach ($this->sonderInvoice->getAll() as $invoice) {

            $tasks = $this->task->getPeriodByClient($invoice['date'], $invoice['dateto'], $invoice['sonder_client_id']);
            foreach ($tasks as $task) {
                $line = $this->sonderInvoiceLine->existBytaskId($task['id']);
                if (!$line) {
                    //id sonder_invoice_id titel price discount quantity 
                    $invoiceline = array();
                    $invoiceline['sonder_invoice_id'] = $invoice['id'];
                    $invoiceline['titel'] = $task['title'];
                    $invoiceline['price'] = '';
                    $invoiceline['discount'] = '0';
                    $invoiceline['quantity'] = $task['billable_hours'];
                    $invoiceline['task_id'] = $task['id'];
                    $invoiceline['sonder_product_id'] = $task['sonder_product_id'];
                    $this->sonderInvoiceLine->save($invoiceline);
                }
            }
        }
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

    public function index()
    {

        $this->generateMonthlyInvoices();

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
        if($this->saveBankFile($contents))
        {
            $this->response->redirect($this->helper->url->to('invoice', 'purchasing', array()));
        }
    }



    public function purchasing()
    {

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'debitcredit' => $this->sonderDebitcredit->getAll(),
            ),
            'title' => 'Finance / Purchasing',
            'sidebar_template' => 'invoice/sidebar',
            'sub_template' => 'invoice/purchasing'
        )));
    }

    public function editpurchasing()
    {

        if (!isset($_GET['id'])) {
            $id = 0;
        } else {
            $id = $_GET['id'];
        }

        $debitcredit = $this->sonderDebitcredit->getById($id);
        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'debitcredit' => $debitcredit[0],
                'users' => $this->user->getAll(),
                'ledgers' => $this->sonderLedger->getAll()
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

    public function showpdf()
    {

        // define("DOMPDF_ENABLE_REMOTE", false);

     //   $client_id = $_GET['client'];
        $id = 1;
        $invoicetotal = 0;
        $lines = array();


        $btw = ($invoicetotal / 100) * 21;
        $invoicetotalinc = $btw + $invoicetotal;
        $duedate = date('d-m-Y', strtotime("+30 days"));
        $number = 'SO1111137';
        $relationumber = '17';

        $invoice = $this->sonderInvoice->getById($_GET['id']);
        $lines = $this->sonderInvoiceLine->getByInvoiceId($invoice['id']);


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
<td style="vertical-align: top;">
            <img style="width:150px;" src="' . $_SERVER["DOCUMENT_ROOT"] . '/logo.jpg" />
                <br /><br /><br />
        </td>
  <td style="vertical-align: top;"></td>
  <td style="vertical-align: top;"></td>
      
</tr>
    <tr>
        <td></td>
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
            <table>
                <tr>
                    <td>'.$invoice['name'].'<br />
                        '.$invoice['contact'].'<br />
                        '.$invoice['adres'].'<br /> 
                        '.$invoice['postcode'].' '.$invoice['city'].'<br /> 
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
                    <td>Factuurnummer: ' . $number . '<br />
                        Factuurdatum: ' . date('d-m-Y') . '</td>
                    <td>Relatienummer: ' . $relationumber . '<br />
                        Vervaldatum: ' . $duedate . '
                    </td>
                    <td></td>
                </tr>
            </table>
        </td>
        <td></td>
        <td></td>
    </tr>
     </table>
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
        foreach ($lines as $line) {

            $product = $this->sonderProduct->getById($line['sonder_product_id']);
            $linetotal = (floatval($product['price']) * floatval($line['quantity']));

            $pdf .= '<tr>
                    <td>' . $line['quantity'] . ' X </td>
                    <td><b>' . $product['title'] . '</b><br />' . $line['titel'] . '</td>
                    <td align="right">EUR ' . number_format((float)$product['price'], 2, ',', '') . '</td>
                    <td align="right">EUR ' . number_format((float)$linetotal, 2, ',', '') . '</td>
                </tr><tr><td colspan="4"><hr /></td></tr>';

            $invoicetotal += $linetotal;
        }

        $btw = ($invoicetotal / 100) * 21;
        $invoicetotalinc = $btw + $invoicetotal;

        $pdf .= '
                <tr>
                    <td></td>
                    <td></td>
                    <td align="right">Subtotaal</td>
                    <td align="right">EUR ' . number_format((float)$invoicetotal, 2, ',', '') . '</td>
                </tr>
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
        <td colspan="3">
            <b>'.$invoice['beschrijvingbottom'].'</b>
            <br /><br /><br />
            Wil je de ' . number_format((float)$invoicetotalinc, 2, ',', '') . ' voor ' . $duedate . ' aan ons overmaken met het factuurnummer ' . $number . ' erbij ?<br /> Ons rekeningnummer is NL65 RABO 0303 5495 21.<br />
            Bel gerust: 06-41844518 of email bart@2sonder.com bij vragen.<br />
        </td>
    </tr>
</table>';

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
        foreach ($this->sonderProduct->getAll() as $product) {
            $products[$product['id']] = $product;
        }

        /*
        foreach ($this->sonderDebitcredit->getAllWithdrawalsByMonthAndUser($datetime, $userid) as $dc) {

        }
        */

        $months = array();
        foreach ($this->task->getAll() as $task) {

            $monthkey = date('Y-m-01', $task['date_due']);
            if (!isset($months[$monthkey])) {
                $months[$monthkey] = array();
            }
            if (!isset($months[$monthkey][$task['owner_id']])) {
                $months[$monthkey][$task['owner_id']] = array();
            }

            if (!isset($months[$monthkey][$task['owner_id']]['producten'])) {
                $months[$monthkey][$task['owner_id']]['producten'] = array();
            }


            if (isset($months[$monthkey][$task['owner_id']]['producten']['billable_hours'])) {
                $billables = $months[$monthkey][$task['owner_id']][$task['sonder_product_id']]['producten']['billable_hours'];
            } else {
                $billables = 0;
            }

            if (isset($months[$monthkey][$task['owner_id']]['invested_hours'])) {
                $invested = $months[$monthkey][$task['owner_id']]['invested_hours'];
            } else {
                $invested = 0;
            }

            if (!isset($months[$monthkey][$task['owner_id']]['uitbetaald'])) {
                $debitcredit = $this->sonderDebitcredit->getAllWithdrawalsByMonthAndUser($monthkey, $task['owner_id']);
                if (count($debitcredit) > 0) {
                    foreach ($debitcredit as $dc) {
                        //    print_r($dc);
                    }
                }
            }


            $months[$monthkey][$task['owner_id']]['invested_hours'] = ($invested + $task['time_spent']);


            if (isset($task['sonder_product_id'])) {
                $months[$monthkey][$task['owner_id']]['producten'][$task['sonder_product_id']]['product'] = $products[$task['sonder_product_id']];
            }


            $months[$monthkey][$task['owner_id']]['producten'][$task['sonder_product_id']]['billable_hours'] = ($billables + $task['billable_hours']);
            $months[$monthkey]['month'] = date('m-Y', strtotime($monthkey));
        }

        $users = array();
        foreach ($this->user->getAll() as $user) {
            $users[$user['id']] = $user;
        }

        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'months' => $months,
                'users' => $users
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

            $product = array('title' => $values['title'],'price' => $values['price']);
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

    public function saveacquisition(){

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

    public function exchangeShortcodes($shortcodes,$text)
    {
        $t = $text;
        foreach(array_keys($shortcodes) as $shortcode)
        {
            $t = str_replace('['.$shortcode.']',$shortcodes[$shortcode],$t);
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

                if (isset($product[0])) {
                    $lines[$index]['product'] = $product[0];
                } else {
                    $lines[$index]['product'] = array('price' => 0, 'title' => '');
                }

                $lines[$index]['price'] = $lines[$index]['product']['price'];
                $lines[$index]['total'] = floatval($lines[$index]['price']) * floatval($lines[$index]['quantity']);
            }

            $shortcodes = array();
            $client = $this->sonderClient->getById($invoice['sonder_client_id']);


            $shortcodes['relatie'] = $client['contact'];
            $shortcodes['maand'] = date('m-Y',strtotime($invoice['date']));


            $invoice['beschrijvingtop'] = $this->exchangeShortcodes($shortcodes,$invoice['beschrijvingtop']);
            $invoice['beschrijvingbottom'] = $this->exchangeShortcodes($shortcodes,$invoice['beschrijvingbottom']);

        } else {
            $lines = array();
            $invoice = array();
        }


        $this->response->html($this->helper->layout->app('invoice/layout', array(
            'data' => array(
                'paginator' => 'page',
                'nb_projects' => 'project',
                'invoice' => $invoice,
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

}
