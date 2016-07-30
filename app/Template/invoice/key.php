<table>
    <tr>
        <th>Month</th>
        <?php foreach($users as $user){ ?>
        <th><?php echo $user['name']; ?></th>
        <?php } ?>
    </tr>
    <?php foreach($months as $month){ ?>
    <tr>
        <td rowspan="4"><?php echo $month['month']; ?></td>
        <?php foreach($users as $user){ ?>
            <th><?php if(isset($month[$user['id']])){ echo $month[$user['id']]['billable_hours']; } ?></th>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach($users as $user){ ?>
            <th><?php echo $user['id']; ?></th>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach($users as $user){ ?>
            <th><?php echo $user['id']; ?></th>
        <?php } ?>
    </tr>
    <tr>
        <?php foreach($users as $user){ ?>
            <th><?php echo $user['id']; ?></th>
        <?php } ?>
    </tr>
    <?php } ?>
    <tr>
        <td></td>
    </tr>
</table>