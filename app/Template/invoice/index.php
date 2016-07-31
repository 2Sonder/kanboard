<a class='btn btn-danger' href="?controller=invoice&action=newinvoice">New invoice</a>
<a class='icon-right' href="?controller=invoice&action=settings"><i class="fa fa-gear  fa-fw"></i></a>
<div class="sidebar-content">
    <table>
        <tr>
            <th>#</th>
            <th>Number</th>
            <th>Client</th>
            <th>Status</th>
            <th>Date</th>
            <th>Period end</th>
        </tr>
        <?php foreach($invoices as $index => $invoice){ ?>
        <tr>
            <td><?php echo $invoice['id']; ?></td>
            <td><a href="?controller=invoice&action=newinvoice&id=<?php echo $invoice['invoiceid']; ?>"><?php echo $invoice['invoicenumber']; ?></a></td>
            <td><?php echo $invoice['name']; ?></td>
            <td><?php echo $invoice['status']; ?></td>
            <td><?php echo date('d-m-Y',strtotime($invoice['date'])); ?></td>
            <td><?php echo date('d-m-Y',strtotime($invoice['dateto'])); ?></td>
        </tr>
        <?php } ?>
    </table> 
</div>    