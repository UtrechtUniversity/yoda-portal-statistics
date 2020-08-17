<h1>Statistics</h1>

<?php if ($isRodsAdmin == 'yes' || $isDatamanager == 'yes') { ?>
<div class="row mb-4">
    <?php if ($isRodsAdmin == 'yes') { ?>
    <div class="col-md-5">
        <div class="card resources">
            <div class="card-header">
                Resources
            </div>
            <div class="list-group" id="resource-list">
                <?php foreach ($resources as $resource) { ?>
                    <a class="list-group-item list-group-item-action resource" data-name="<?php echo $resource['name']; ?>">
                        <?php echo $resource['name']; ?>
                        <small class="float-right resource-tier" title="<?php echo htmlentities($resource['tier']); ?>">
                            <?php echo (strlen($resource['tier']) > 10 ? htmlentities(substr($resource['tier'], 0, 10)) . '...' : $resource['tier']); ?>
                        </small>
                    </a>
                <?php } ?>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="col-md-7">
        <?php if ($isRodsAdmin == 'yes') { ?>
        <div class="resource-details">
            <div class="card">
                <div class="card-header">
                    Resource properties
                </div>
                <div class="card-body">
                    <p class="placeholder-text">
                        Please select a resource.
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Storage (RodsAdmin)
            </div>
            <div class="card-body">
                <?php echo $storageTableAdmin; ?>

                <a href="<?php echo base_url('statistics/export') ?>" class="btn btn-primary btn-sm">
                    Export
                </a>
            </div>
        </div>
        <?php } ?>

        <?php if ($isDatamanager == 'yes') { ?>
        <div class="card">
            <div class="card-header">
                Storage (Datamanager)
            </div>
            <div class="card-body">
                <?php echo $storageTableDatamanager; ?>

                <a href="<?php echo base_url('statistics/export') ?>" class="btn btn-primary btn-sm">
                    Export all details
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>

<?php if ($isResearcher == 'yes' || $isDatamanager == 'yes') { ?>
<?php
    function human_filesize($bytes, $decimals = 2) {
        $size = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }
?>
<div class="row">
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                Groups
            </div>
            <div class="list-group" id="groups-list">
                <?php foreach ($groups as $name) { ?>
                    <?php if ($isDatamanager == 'yes') { // datamanager get to see current storage levels per group ?>
                        <a class="list-group-item list-group-item-action group" data-name="<?php echo $name[0]; ?>">
                            <?php echo $name[0] . ($name[1]>0 ? (' ('  . human_filesize($name[1]) . ', ' . $name[1] . ')'):''); ?>
                        </a>
                    <?php }
                    else { ?>
                    <a class="list-group-item list-group-item-action group" data-name="<?php echo $name; ?>">
                        <?php echo $name; ?>
                    </a>
                    <?php } ?>
                <?php } ?>
            </div>
            <div class="card-footer">
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="group-details">
            <div class="card">
                <div class="card-header">
                    Group
                </div>
                <div class="card-body">
                    <p class="placeholder-text">
                        Please select a group.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
