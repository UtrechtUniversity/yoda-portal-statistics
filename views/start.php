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
<div class="row">
    <div class="col-md-5">
        <div class="panel panel-default resources">
            <div class="panel-heading clearfix">
                <h3 class="panel-title pull-left">Groups</h3>
            </div>
            <div class="list-group" id="groups-list">
                <?php foreach ($groups as $name) { ?>
                    <div class="list-group">
                        <a class="list-group-item group" data-name="<?php echo $name; ?>">
                            <?php echo $name; ?>
                        </a>
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