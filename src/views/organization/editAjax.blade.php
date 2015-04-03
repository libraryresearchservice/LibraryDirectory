<!-- editOrganizationAjax -->

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="model-title"><?php echo $org->id ? 'Edit' : 'Create' ?> Organization</h4>
</div>
<form class="form-horizontal" id="save-org-ajax" method="post" action="<?php echo url() ?>/admin/organization/save" role="form">
    <div class="modal-body">
		<?php
		foreach ( $orgElements as $k => $v ) {
            if ( $v['is_hidden'] == 1 ) {
                ?>
                <input type="hidden" name="<?php echo $v['code'] ?>" value="<?php echo $org->{strtolower($v['code'])} ?>" />
                <?php
            }
        }
        foreach ( $orgElements as $k => $v ) {
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
                        <input type="text" class="form-control" name="<?php echo $v['code'] ?>" id="<?php echo $v['code'] ?>" value="<?php echo $org->{strtolower($v['code'])} ?>">
                        <?php
                    } else if ( $v['data_type'] == 'textarea' ) {
						?>
						<textarea name="<?php echo $v['code'] ?>" class="form-control" id="<?php echo $v['code'] ?>" rows="4"><?php echo $org->{strtolower($v['code'])} ?></textarea>
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
        <input type="submit" id="save-org-ajax-submit" class="btn btn-primary pull-left" value="Save">
        <?php
		if ( $org->id ) {
			?>
        	&nbsp&nbsp;&nbsp;<input type="submit" name="delete" id="delete-org-ajax-submit" class="btn btn-default" value="Delete">
    		<?php
		}
		?>
    </div>
</form>