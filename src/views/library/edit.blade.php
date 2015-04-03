@extends('librarydirectory::master')

@section('content')

<!-- editLibrary -->

<ol class="breadcrumb">
	<?php
	if ( $library->id ) {
		?>
        <li><a href="<?php echo url() ?>">Libraries</a></li>
        <li><a href="<?php echo url() ?>?organization_id=<?php echo $library->organization_id ?>"><?php echo $library->organization_name ?></a></li>
        <li class="active"><a href="<?php echo url() ?>/library/view/<?php echo $library->id ?>"><?php echo $library->name ? ucwords(strtolower($library->name)) : 'Central/Main Location' ?></a></li>
        <li class="active">Edit</li>
        <?php
	} else {
		?>
        <li class="active">Create</li>
        <?php
	}
	?>
</ol>

<?php 
displayFlashMessages($errors);
if ( $public ) {
	?>
    <div class="row">
        <div class="col-md-12">
        	<div class="alert alert-info text-center">Thank you for contributing to the Directory. Your changes will be reviewed by State Library staff and, if approved, may take 2-3 business days to appear.</div>
    	</div>
	</div>
	<?php	
}
if ( $isPublicEdit ) {
	foreach ( array('edit_message' => 'Edit Message', 'edit_organization_name' => 'Organization Name Change') as $k => $v ) {
		if ( isset($publicEdit[$k]) && $publicEdit[$k] != '' ) {
			?>
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-warning" role="alert">
						<?php echo $v ?>: <?php echo $publicEdit[$k] ?>
					</div>
				</div>
			</div>
			<?php
		}	
	}
}
?>
<div class="row">
    <div class="col-md-<?php echo $isPublicEdit ? 6 : 12 ?>">
        <form class="form-horizontal" id="edit-library" action="<?php echo url() ?>/library/<?php echo $public ? 'public-' : '' ?>save" method="post" role="form">
			<?php
            foreach ( $groupsAndElements as $groupKey => $group ) {
                ?>
                <div class="panel panel-default edit-panel">
                    <div class="panel-heading"><?php echo $group['name'] ?></div>
                    <div class="panel-body">
						<?php
                        foreach ( $group['elements'] as $k => $v ) {
							if ( $v['is_hidden'] == 1 ) {
								?>
								<input type="hidden" name="<?php echo $v['code'] ?>" value="<?php echo $library->{strtolower($v['code'])} ?>" />
								<?php
							}
						}
						foreach ( $group['elements'] as $k => $v ) {
							echo displayFormElement($v, $library, $isPublicEdit);
						}
					?>
                    </div>
                </div>
                <?php
			}
            if ( $isPublicEdit ) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">Public Edit</div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="delete-public-edit">Delete Public Edit?</label>
                            <div class="col-sm-3">
                                <input type="hidden" name="public-edit-id" value="<?php echo $isPublicEdit ?>">
                                <select name="delete-public-edit" class="form-control" id="delete-public-edit">
                                    <?php
                                    foreach ( array('' => '...select', 1 => 'Yes', 0 => 'No') as $k1 => $v1 ) {
                                        ?>
                                        <option value="<?php echo $k1 ?>"><?php echo $v1 ?></option>
                                        <?php	
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?php	
            }
            ?>
            <div class="staff-edit-wrap col-md-<?php echo $isPublicEdit ? 6 : 12 ?>">
               <!-- SPAM honeypot -->
                <div style="display:none">
                    <p><strong>Who is your favorite Bronco? (Human Beings Should Ignore This)</strong><br /><input type="text" name="bronco" id="bronco" value=""></p>
                </div>
                <input type="submit" class="btn btn-primary" value="Save">
				<?php
                if ( Auth::check() && $library->id ) {
                    ?>
                    <input type="submit" name="delete" class="btn btn-default btn-sm pull-right" value="Delete">
                    <?php	
                }
                ?>
        	</div>
        </form>
	</div>
    <?php
	if ( $isPublicEdit ) {
		?>
        <!-- Public edit -->
        <div class="staff-edit-wrap col-md-6">
            <form class="form-horizontal" id="edit-library" action="<?php echo url() ?>/library/<?php echo $public ? 'public-' : '' ?>save" method="post" role="form">
                <?php
                foreach ( $groupsAndElements as $groupKey => $group ) {
                    ?>
                    <div class="panel panel-default edit-panel">
                        <div class="panel-heading"><?php echo $group['name'] ?></div>
                        <div class="panel-body">
                            <?php
                            foreach ( $group['elements'] as $k => $v ) {
								echo displayFormElement($v, $publicEdit, $isPublicEdit, true);
                            }
                        	?>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </form>
        </div>
        <?php	
	}
	?>
</div>

<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	var editLibraryAntiSPAM = '<input type="hidden" name="lulz" value="<?php echo simpleAntiSpamToken() ?>">';
	$('#edit-library').append(editLibraryAntiSPAM);
});
</script>

@stop