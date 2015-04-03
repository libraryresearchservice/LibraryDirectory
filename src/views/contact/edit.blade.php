@extends('librarydirectory::master')

@section('content')

<!-- editLibrary -->

<ol class="breadcrumb">
	<?php
	if ( $contact->id ) {
		?>
        <li><a href="<?php echo url() ?>/contact">Contacts</a></li>
        <li><a href="<?php echo url() ?>/contact?organization_id=<?php echo $contact->organization_id ?>"><?php echo $contact->organization_name ?></a></li>
        <li class="active"><a href="<?php echo url() ?>/contact/view/<?php echo $contact->id ?>"><?php echo $contact->name ?></a></li>
        <li class="active">Edit</li>
        <?php
	} else {
		?>
        <li class="active">Create</li>
        <?php
	}
	?>
</ol>

<?php displayFlashMessages($errors) ?>

<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" id="edit-library" action="<?php echo url() ?>/contact/save" method="post" role="form">
			<?php
			/**
			 *	Dave hates building forms manually!
			 */
			$type = substr($contact->type, 0, 3);
            foreach ( $groupsAndElements as $groupKey => $group ) {
                ?>
                <div class="panel panel-default">
                    <div class="panel-heading"><?php echo $group['name'] ?></div>
                    <div class="panel-body">
						<?php
                        foreach ( $group['elements'] as $k => $v ) {
							if ( $v['is_hidden'] == 1 ) {
								?>
								<input type="hidden" name="<?php echo $v['code'] ?>" value="<?php echo $contact->{strtolower($v['code'])} ?>" />
								<?php
							}
						}
						foreach ( $group['elements'] as $k => $v ) {
							if ( $v['is_hidden'] == 1 || $v['is_active'] == 0 ) {
								continue;
							}
							$code = $v['code'];
							$defaultValue = $v['default_value'];
							$name = $v['name'];
							$type = $v['data_type'];
							$value = $contact->{strtolower($v['code'])};
							$length = $v['input_size'] > 0 ? $v['input_size'] : 6;
							?>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="<?php echo $code ?>"><?php echo $name != '' ? $name : $code ?></label>
								<div class="col-sm-<?php echo $length ?>">
								<?php
								if ( $type == 'text') {
									?>
									<input type="text" class="form-control" name="<?php echo $code ?>" id="<?php echo $code ?>" value="<?php echo $value ?>">
									<?php
								} else if ( $type == 'textarea' ) {
									?>
									<textarea name="<?php echo $code ?>" class="form-control" id="<?php echo $code ?>" rows="4"><?php echo $value ?></textarea>
									<?php
								} else if ( $type == 'bool' ) {
									?>
									<select name="<?php echo $code ?>" class="form-control" id="<?php echo $code ?>">
										<?php
										foreach ( array('' => '...select', 1 => 'Yes', 0 => 'No') as $k1 => $v1 ) {
											$selected = false;
											if ( is_numeric($value) && $value == $k1 ) {
												$selected = true;
											} else if ( $defaultValue != '' && $k1 == $defaultValue ) {
												$selected = true;
											}
											?>
											<option value="<?php echo $k1 ?>"<?php echo $selected ? ' selected' : '' ?>><?php echo $v1 ?></option>
											<?php	
										}
										?>
									</select>
									<?php
								} else if ( substr($type, 0, 4) == 'text' ) {
									?>
									<input type="text" class="form-control" name="<?php echo $code ?>" id="<?php echo $code ?>" value="<?php echo $value ?>" size="<?php echo $length ?>">
									<?php
								} else if ( substr($type, 0, 4) == 'func' ) {
									$function = str_replace('func', '', $type);
									if ( function_exists($function) ) {
										echo $function($code, $value, $defaultValue);	
									}
								}
								?>
                                </div>
                            </div>
							<?php
						}
					?>
                    </div>
                </div>
                <?php
			}
			?>
            <div style="display:none">
                <p><strong>Who is your favorite Bronco? (Human Beings Should Ignore This)</strong><br /><input type="text" name="bronco" id="bronco" value=""></p>
            </div>
            <input type="submit" class="btn btn-primary" value="Save">
            <?php
            if ( Auth::check() && $contact->id ) {
                ?>
                <input type="submit" name="delete" class="btn btn-default btn-sm pull-right" value="Delete">
                <?php	
            }
            ?>
        </form>
		<script type="text/javascript">
        $(document).ready(function() {
			var editLibraryAntiSPAM = '<input type="hidden" name="lulz" value="<?php echo simpleAntiSpamToken() ?>">';
            $('#edit-library').append(editLibraryAntiSPAM);
        });
        </script> 
	</div>
</div>

<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

@stop