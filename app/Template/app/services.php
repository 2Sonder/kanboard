<?php /*[id] => 3[title] => Regular client (60 hourly)[description] =>[date_creation] => 1469918875[date_completed] =>[date_due] => 0[color_id] => blue[project_id] => 12[column_id] => 47[owner_id] => 1[position] => 2[score] => 4[is_active] => 1[category_id] => 0[creator_id] => 1[date_modification] => 1470017939[reference] =>[date_started] => 0[time_spent] => 0[time_estimated] => 4[swimlane_id] => 0[date_moved] => 1469922727[recurrence_status] => 0[recurrence_trigger] => 0[recurrence_factor] => 0[recurrence_timeframe] => 0[recurrence_basedate] => 0[recurrence_parent] =>[recurrence_child] =>[priority] => 0[billable_hours] => 3[sonder_client_id] => 4[sonder_product_id] => 3[sonder_parent_client_id] => 4[name] => Barnworks algemeen[token] => b058d23e040adc30d181d01f4e5cbff019b1ece3aa45c2017d893bb26ad9[last_modified] => 1470060310[is_public] => 1[is_private] => 0[is_everybody_allowed] => 0[default_swimlane] => Default swimlane[show_default_swimlane] => 1[identifier] =>[start_date] =>[end_date] =>[priority_default] => 0[priority_start] => 0[priority_end] => 3[price] => 60.00[producttitle] => Regular client (60 hourly)[tasktitle] => test*/ ?>
<div>
    <style>        .financial {
            display: none;
        }

        th {
            vertical-align: top;
        }    </style>
    <div><input type="checkbox" id="enable-financial-columns"/> Enable financial columns</div>
    <table>
        <tr>
            <th class="column-10 financial">Product</th>
            <th class="column-5 financial">Price St.</th>
            <th class="column-10">Task</th>
            <th class="column-10">Description</th>
            <th class="column-5">Status</th>
            <th class="column-8">created</th>
            <th class="column-5">Estimated hours</th>
            <th class="column-5 financial">Prijs schatting</th>
            <th class="column-8">Completed</th>
            <th class="column-10 financial">Billable hours<br/>
                <p style="font-size: 13px;">Name &nbsp;&nbsp;&nbsp; Hours X Rate = Subtotal</p></th>
        </tr> <?php if (count($tasks) > 0) {
            $lastcompleted = '';
            $totalEstimate = 0;
            $totalBillable = 0;
            $openTasks = array();
            $closedTasks = array();
            foreach ($tasks as $task) {
                $date = date('m-Y', $task['date_completed']);
                if ($date != $lastcompleted) {
                    $lastcompleted = $date;
                }
                if (strlen($task['date_completed']) < 2) {
                    $openTasks[] = $task; ?><?php } else {
                    $closedTasks[$lastcompleted][] = $task; ?><?php }
            } ?>
            <tr>
                <td colspan="9"><h4>Open tasks</h4></td>
            </tr>            <?php foreach ($openTasks as $task) {
                $estimatedCost = $task['time_estimated'] * $task['price'];
                $taskBillableHours = 0; ?>
                <tr>
                    <td class="financial"><?php echo $task['producttitle']; ?></td>
                    <td class="financial">€<?php echo $task['price']; ?></td>
                    <td><?php echo $task['tasktitle']; ?></td>
                    <td><?php echo $task['description']; ?></td>
                    <td><?php echo $task['columntitle']; ?></td>
                    <td><?php echo date('d-m-Y', $task['date_creation']); ?></td>
                    <td><?php echo $task['time_estimated']; ?></td>
                    <td align="right"
                        class="financial">€<?php echo number_format((float)$estimatedCost, $decimals = 2); ?></td>
                    <td align="right"><?php if ($task['date_completed'] != null) {
                            echo date('d-m-Y', $task['date_completed']);
                        } ?></td>
                    <td class="financial">                        <?php if (count($task['billable_hours']) > 0) { ?>
                            <table>                                <?php foreach ($task['billable_hours'] as $user) {
                                    $taskBillableHours += $user['hours'] * $task['price']; ?>
                                    <tr>
                                        <td style="border: none;padding: 0px;"><?php echo $user['name']; ?></td>
                                        <td style="border: none;padding: 0px;"><?php echo number_format((float)$user['hours'], 1, '.', ''); ?>
                                            X €<?php echo $task['price']; ?></td>
                                    </tr>                                <?php } ?>
                            </table>                        <?php } ?>                    </td>
                </tr>                <?php $totalBillable += $taskBillableHours;
                $totalEstimate += $estimatedCost; ?><?php } ?>
            <tr>
                <td class="financial"></td>
                <td class="financial"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><p class="financial">Total Estimate:</p></td>
                <td class="financial" align="right">€<?php echo number_format($totalEstimate, $decimals = 2); ?></td>
                <td></td>
                <td class="financial">Total Billable: €<?php echo number_format($totalBillable, $decimals = 2); ?></td>
            </tr>            <?php foreach ($closedTasks as $key => $tasksClosed) {
                $totalEstimate = 0;
                $totalBillable = 0;                //print_r($closedTasks);                ?>
                <tr>
                    <td colspan="9"><h4><? echo $key; ?></h4></td>
                </tr>                <?php foreach ($tasksClosed as $task) {
                    $estimatedCost = $task['time_estimated'] * $task['price'];
                    $taskBillableHours = 0; ?>
                    <tr>
                        <td class="financial"><?php echo $task['producttitle']; ?></td>
                        <td class="financial">€<?php echo $task['price']; ?></td>
                        <td><?php echo $task['tasktitle']; ?></td>
                        <td><?php echo $task['description']; ?></td>
                        <td><?php echo $task['columntitle']; ?></td>
                        <td><?php echo date('d-m-Y', $task['date_creation']); ?></td>
                        <td><?php echo $task['time_estimated']; ?></td>
                        <td class="financial"
                            align="right">€<?php echo number_format((float)$estimatedCost, $decimals = 2); ?></td>
                        <td><?php if ($task['date_completed'] != null) {
                                echo date('d-m-Y', $task['date_completed']);
                            } ?></td>
                        <td class="financial">                            <?php if (count($task['billable_hours']) > 0) { ?>
                                <table>                                    <?php foreach ($task['billable_hours'] as $user) {
                                        $taskBillableHours += $user['hours'] * $task['price']; ?>
                                        <tr>
                                            <td style="border: none;padding: 0;"><?php echo $user['name']; ?></td>
                                            <td style="border: none;padding: 0px;"><?php echo number_format((float)$user['hours'], 1, '.', ''); ?>
                                                X €<?php echo $task['price']; ?></td>
                                        </tr>                                    <?php } ?>
                                </table>                            <?php } ?>                        </td>
                    </tr>                    <?php $totalBillable += $taskBillableHours;
                    $totalEstimate += $estimatedCost; ?><?php } ?>
                <tr>
                    <td class="financial"></td>
                    <td class="financial"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><p class="financial">Total Estimate:</p></td>
                    <td class="financial" align="right">€<?php echo number_format($totalEstimate, $decimals = 2); ?></td>
                    <td></td>
                    <td class="financial">Total
                        Billable: €<?php echo number_format($totalBillable, $decimals = 2); ?></td>
                </tr>                <?php }
        } ?>    </table>
</div>