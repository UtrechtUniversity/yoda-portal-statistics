<div class="panel panel-default properties">
    <div class="panel-heading">
        <h3 class="panel-title">Group storage (<?php echo $name; ?>)</h3>
    </div>
    <div class="panel-body">
        <?php if ($storageData['totalStorage'] == 0) { ?>
            <p>No storage information found.</p>
        <?php } else { ?>
            <canvas data-storage="<?php echo $showStorage; ?>" class="storage-data" width="400" height="400"></canvas>
        <?php } ?>
    </div>
</div>