<a class='btn btn-danger' href="?controller=invoice&action=newinvoice">New invoice</a>
<a class='icon-right' href="?controller=invoice&action=settings"><i class="fa fa-gear  fa-fw"></i></a>
<div class="sidebar-content">
    <table>
        <tr>
            <th>#</th>
            <th>Number</th>
            <th>Client</th>
            <th>Status</th>
            <th>Total excl.</th>
            <th>Total inc.</th>
            <th>Date</th>
            <th>Period end</th>
            <th>Contract</th>
        </tr>
        <?php foreach($invoices as $index => $invoice){ ?>
        <tr>
            <td><?php echo $invoice['id']; ?></td>
            <td><a href="?controller=invoice&action=newinvoice&id=<?php echo $invoice['id']; ?>"><?php echo $invoice['invoicenumber']; ?></a></td>
            <td><?php echo $invoice['clientname']; ?></td>
            <td><?php echo $invoice['status']; ?></td>
            <td><?php echo $invoice['totalexcl']; ?></td>
            <td><?php echo $invoice['totalinc']; ?></td>
            <td><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
            <td><?php echo date('d-m-Y',strtotime($invoice['dateto'])); ?></td>
            <td><a href="?controller=invoice&action=editcontract&id=<?php echo $invoice['contractid']; ?>"><?php echo $invoice['contractname']; ?></a></td>
        </tr>
        <?php } ?>
    </table> 
</div>    