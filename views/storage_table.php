<table class="table table-striped storage-table">
    <tr>
        <th>Category</th>
        <th>Tier</th>
        <th>Storage</th>
    </tr>
    <?php if (is_array($data)) { ?>
        <?php foreach($data as $row) { ?>
            <tr>
                <td><?php echo $row['category']; ?></td>
                <td><?php echo $row['tier']; ?></td>
                <td><?php echo roundUpBytes(bytesToTerabytes((int) $row['storage']), 1); ?> TB | <?php echo (int) $row['storage']; ?> bytes</td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="3">No data found.</td>
        </tr>
    <?php } ?>
</table>