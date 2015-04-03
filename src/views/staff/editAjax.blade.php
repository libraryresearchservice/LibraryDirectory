<!-- editStaffAjax -->

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="model-title"><?php echo $staff->staffid ? 'Edit' : 'Create' ?> Staff</h4>
</div>
<form class="form-horizontal" id="save-staff-ajax" method="post" action="<?php echo url() ?>/staff/save" role="form">
    <input type="hidden" name="return-to" value="<?php echo Input::get('return-to', false) ?>">
    <div class="modal-body">
		<?php
        foreach ( $staffElements as $k => $v ) {
            if ( $v['is_hidden'] == 1 ) {
                ?>
                <input type="hidden" name="<?php echo $v['code'] ?>" value="<?php echo $staff->{strtolower($v['code'])} ?>" />
                <?php
            }
        }
		?>
        <div class="form-group">
            <div class="col-sm-12">
                <label for="libinst">Organization</label>
                <input type="text" id="libinst" class="form-control" value="<?php echo $staff->organization_name ?>" disabled>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-12">
                <label for="libranch">Library</label>
                <input type="text" id="libranch" class="form-control" value="<?php echo $staff->libranch ?>" disabled>
            </div>
        </div>
        <?php
        foreach ( $staffElements as $k => $v ) {
            if ( $v['is_hidden'] == 1 || $v['is_active'] == 0 ) {
                continue;
            }
            ?>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="<?php echo $v['code'] ?>"><?php echo $v['name'] != '' ? $v['name'] : $v['code'] ?></label>
                    <?php
                    if ( $v['data_type'] == 'text' ) {
                        ?>
                        <input type="text" class="form-control" name="<?php echo $v['code'] ?>" id="<?php echo $v['code'] ?>" value="<?php echo $staff->{strtolower($v['code'])} ?>">
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="modal-footer">
        <input type="submit" id="save-staff-ajax-submit" class="btn btn-primary pull-left" value="Save">&nbsp&nbsp;&nbsp;
        <input type="submit" name="delete" id="delete-staff-ajax-submit" class="btn btn-default" value="Delete">
    </div>
</form>