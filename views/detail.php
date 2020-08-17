<div class="card properties">
    <div class="card-header">
        Resource properties (<?php echo htmlentities($name); ?>)
    </div>
    <div class="card-body">
        <form method="POST" id="resource-properties-form">
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <p class="form-control-static"><?php echo htmlentities($name); ?></p>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 col-form-label">Tier</label>
                <div class="col-sm-8">
                    <input type="text" class="tier-select" value="<?php echo htmlentities($tier); ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-offset-4 col-sm-8">
                    <input class="btn btn-primary update-resource-properties-btn" type="submit" value="Update">
                </div>
            </div>
        </form>
    </div>
</div>
