<h1>Statistics</h1>

<div class="row">
    <?php if ($isRodsAdmin == 'yes') { ?>
    <div class="col-md-5">
        <div class="panel panel-default resources">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Resources</h3>
            </div>
            <div class="list-group" id="resource-list">
                <?php foreach ($resources as $resource) { ?>
                    <div class="list-group">
                        <a class="list-group-item resource" data-name="<?php echo $resource['name']; ?>">
                            <?php echo $resource['name']; ?>
                            <small class="pull-right resource-tier" title="<?php echo htmlentities($resource['tier']); ?>">
                                <?php echo (strlen($resource['tier']) > 10 ? htmlentities(substr($resource['tier'], 0, 10)) . '...' : $resource['tier']); ?>
                            </small>
                        </a>
                    </div>
                <?php } ?>
            </div>
            <div class="panel-footer clearfix">
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="col-md-7">
        <?php if ($isRodsAdmin == 'yes') { ?>
        <div class="resource-details">
            <div class="panel panel-default properties">
                <div class="panel-heading">
                    <h3 class="panel-title">Resource properties</h3>
                </div>
                <div class="panel-body">
                    <p class="placeholder-text">
                        Please select a resource.
                    </p>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Storage (RodsAdmin)</h3>
            </div>
            <div class="panel-body">
                <?php echo $storageTableAdmin; ?>

                <a href="<?php echo base_url('statistics/export') ?>" class="btn btn-primary btn-sm">
                    Export
                </a>
            </div>
        </div>
        <?php } ?>

        <?php if ($isDatamanager == 'yes') { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Storage (Datamanager)</h3>
            </div>
            <div class="panel-body">
                <?php echo $storageTableDatamanager; ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

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
        <div class="panel panel-default resources">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Groups</h3>
            </div>
            <div class="list-group" id="groups-list">
                <?php foreach ($groups as $name) { ?>
                    <div class="list-group">
                        <?php if ($isDatamanager == 'yes') { // datamanager get to see current storage levels per group ?>
                            <a class="list-group-item group" data-name="<?php echo $name[0]; ?>">
                                <?php echo $name[0] . ($name[1]>0 ? (' ('  . human_filesize($name[1]) . ', ' . $name[1] . ')'):''); ?>
                            </a>
                        <?php }
                        else { ?>
                        <a class="list-group-item group" data-name="<?php echo $name; ?>">
                            <?php echo $name; ?>
                        </a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="panel-footer clearfix">
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="group-details">
            <div class="panel panel-default properties">
                <div class="panel-heading">
                    <h3 class="panel-title">Group</h3>
                </div>
                <div class="panel-body">
                    <p class="placeholder-text">
                        Please select a group.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>