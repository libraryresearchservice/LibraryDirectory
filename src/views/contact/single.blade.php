@extends('librarydirectory::master')

@section('content')

<!-- library -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>/contact">Contacts</a></li>
    <li><a href="<?php echo url() ?>/contact?organization_id=<?php echo $contact->organization_id ?>"><?php echo $contact->organization_name ?></a></li>
    <li><a href="<?php echo url() ?>/contact?library_id=<?php echo $contact->id ?>"><?php echo $contact->name ? $contact->name : 'Central/Main Location' ?></a></li>
    <li class="active"><?php echo $contact->name ?></li>
</ol>

<?php displayFlashMessages($errors) ?>

<div class="library-meta">
	<?php
	foreach ( $groupsAndElements as $groupKey => $group ) {
	   ?>
		<div class="panel panel-default">
			<div class="panel-heading"><?php echo $group['name'] ?></div>
			<div class="panel-body">
				<?php
				foreach ( $group['elements'] as $k => $v ) {
					$display = false;
					$value = $contact->{$v['code']};
					if ( $v['display_type'] == 'raw' ) {
						$display = $value;
					} else if ( $v['display_type'] == 'bool' ) {
						$display = $value ? 'Yes' : 'No';
					} else if ( $v['display_type'] && substr($v['display_type'], 0, 4) == 'func' ) {
						$function = str_replace('func', '', $v['display_type']);
						if ( function_exists($function) ) {
							$display = $function($value, $contact);	
						}
					} else if ( $v['display_type'] && $contact->{$v['display_type']} ) {
						$display = $contact->{$v['display_type']};
					} else {
						$display = $value;
					}
					if ( $display == false || $display == NULL ) {
						$display = '<em>-</em>';	
					}
					?>
					<div class="row">
						<div class="col-md-3">
							<?php echo $v['name'] ?>
						</div>
						<div class="col-md-7">
							<?php echo $display ?>
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
</div>
<div class="row">
    <div class="col-md-12">
        <a href="<?php echo url() ?>/contact/edit/<?php echo $contact->id ?>" class="btn btn-primary">Edit</a>
    </div>
</div>
<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

@stop