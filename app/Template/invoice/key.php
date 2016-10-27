<ul>
    <li>Overview excluding taxes.</li>
</ul>
<table>
    <tr>
        <? foreach ($headers as $header) {
            ?>
            <th><?php echo $header; ?></th><?
        } ?>
    </tr>
    <? foreach ($months as $month) { ?>
        <tr>
            <td><?php echo $month['month']; ?></td>
            <td><?php if($month['sharedtotal'] > 0){ echo $month['sharedtotal']; } ?></td>
            <? foreach ($month['users'] as $user) {
                ?>
                <td>
                    <?php if (isset($user['investedhours']) && $user['investedhours'] > 0) { ?>
                        invested hours: <?php echo $user['investedhours']; ?>
                    <?php }
                    if (isset($user['billablehours']) && count($user['billablehours']) > 0) { ?>
                        billable hours:
                        <?php  foreach ($user['billablehours'] as $rate) {
                            ?><?php echo $rate['value']; ?> &nbsp; <?php echo $rate['key']; ?><br/><?
                        } ?>
                    <?php } ?>

                    <?php if(isset($user['debit']) && $user['debit'] > 0){ ?>
                        <div>
                            cost:<?php echo $user['debit']; ?>
                        </div>
                    <?php } ?>
                </td>
                <?
            } ?>
        </tr>
        <tr><td colspan="<?php echo count($headers); ?>"><hr/></td></tr>
    <? } ?>
</table>
