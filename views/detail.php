<div class="panel panel-default properties">
    <div class="panel-heading">
        <h3 class="panel-title">Resource properties (<?php echo $name; ?>)</h3>
    </div>
    <div class="panel-body">
        <form method="POST" class="form-horizontal" id="resource-properties-form">
            <div class="form-group">
                <label class="col-sm-4 control-label">Name</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo $name; ?></p>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Tier</label>
                <div class="col-sm-8">
                    <input type="text" class="tier-select" value="<?php echo $tier; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <input class="btn btn-primary update-resource-properties-btn" type="submit" value="Update">
                </div>
            </div>
        </form>
    </div>
</div>