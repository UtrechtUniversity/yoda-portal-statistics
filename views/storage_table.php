<table class="table table-striped storage-table">
    <tr>
        <th>Category</th>
        <th>Tier</th>
        <th>Storage</th>
    </tr>
    <?php foreach($data as $row) { ?>
        <tr>
            <td><?php echo $row['category']; ?></td>
            <td><?php echo $row['tier']; ?></td>
            <td><?php echo round_up(bytesToGigabytes((int) $row['storage']), 1); ?> GB | <?php echo (int) $row['storage']; ?> bytes</td>
        </tr>
    <?php } ?>
</table>