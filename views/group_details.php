<div class="card properties">
    <div class="card-header">
        Group storage (<?php echo $name; ?>)
    </div>
    <div class="card-body">
        <?php if (!isset($storageData['totalStorage']) || $storageData['totalStorage'] == 0) { ?>
            <p>No storage information found.</p>
        <?php } else { ?>
            <canvas data-storage="<?php echo $showStorage; ?>" class="storage-data" width="400" height="400"></canvas>
        <?php } ?>
    </div>
</div>