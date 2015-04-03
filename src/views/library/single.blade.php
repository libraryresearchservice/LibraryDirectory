@extends('librarydirectory::master')

@section('content')

<!-- library -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>">Libraries</a></li>
    <li><a href="<?php echo url() ?>?organization_id=<?php echo $library->organization_id ?>"><?php echo $library->organization_name ?></a></li>
    <li class="active"><?php echo $library->name ? ucwords(strtolower($library->name)) : 'Central / Main Location' ?></li>
</ol>

<?php displayFlashMessages($errors) ?>

<?php
$view = Input::get('view');
if ( Auth::check() ) {
	?>
    <ul class="nav nav-tabs text-right" role="tablist">
        <li<?php echo !$view ? ' class="active"' : '' ?>><a href="<?php echo current(explode('?', Request::url())) ?>">Basic</a></li>
        <li<?php echo $view == 'expanded' ? ' class="active"' : '' ?>><a href="<?php echo Request::url() ?>?view=expanded">Expanded</a></li>
        <li<?php echo $view == 'contacts' ? ' class="active"' : '' ?>><a href="<?php echo Request::url() ?>?view=contacts">Contacts</a></li>
    </ul>
    <br />
    <?php	
}
if ( !Auth::check() || !$view || $view == 'expanded' ) {
	
	?>
	
    <div class="library-meta">
        <?php
		foreach ( $libraryGroupsAndElements as $groupKey => $group ) {
		   ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<?php echo $group['name'] ?>
                </div>
				<div class="panel-body">
					<?php
					if ( stristr($group['text_before'], 'func') !== false ) {
						$func = str_replace('func', '', $group['text_before']);
						$func($library);
					}
					foreach ( $group['elements'] as $k => $v ) {
						$display = false;
						$value = $library->{$v['code']};
						if ( $v['display_type'] == 'raw' ) {
							$display = $value;
						} else if ( $v['display_type'] == 'bool' ) {
							$display = $value ? 'Yes' : 'No';
						} else if ( $v['display_type'] && substr($v['display_type'], 0, 4) == 'func' ) {
							$function = str_replace('func', '', $v['display_type']);
							if ( function_exists($function) ) {
								$display = $function($value, $library);	
							}
						} else if ( $v['display_type'] && $library->{$v['display_type']} ) {
							$display = $library->{$v['display_type']};
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
					if ( stristr($group['text_after'], 'func') !== false ) {
						$func = str_replace('func', '', $group['text_after']);
						$func($library);
					}
					?>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	if ( Auth::check() ) {
		?>
		<div class="row">
			<div class="col-md-12">
				<a href="<?php echo url() ?>/library/edit/<?php echo $library->id ?>" class="btn btn-primary">Edit</a>
			</div>
		</div>
		<?php
	}
	if ( !Auth::check() ) {
		?>
		<script type="text/javascript">
		$(document).ready(function() {
			var publicEditMsg = '<p>Spot a mistake? Let us know!</p>' + 
								'<p><a href="<?php echo url() ?>/library/public-edit/<?php echo $library->id ?>" class="btn btn-primary">Edit this Record</a></p>';
			$('.public-edit').append(publicEditMsg);
		});
		</script>
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<div class="alert alert-info public-edit text-center">
				
				</div>
			</div>
		</div>
		<?php
	}
} else if ( $view == 'contacts' ) {
	?>
    <div class="library-meta">
        <div class="row">
            <div class="col-md-12 text-right">
                <a class="btn btn-primary clearfix" href="<?php echo url() ?>/contact/create/<?php echo $library->id ?>">Create Contact</a>
            </div>
        </div> 
        <div class="clearfix"></div>
        <?php
        $suppress = array('organization_id', 'library_id');
        $library->load('contacts.types');
		if ( sizeof($library->contacts) > 0 ) {
            ?>
            <div class="row">
            <?php
            foreach ( $library->contacts as $contact ) {
				?>
                <div class="col-md-6">
                    <?php
                    foreach ( $contactGroupsAndElements as $groupKey => $group ) {
                       ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
								<?php echo $contact->name ?>
                                <div class="pull-right">
                                    <a href="<?php echo url() ?>/contact/edit/<?php echo $contact->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <?php
                                foreach ( $group['elements'] as $k => $v ) {
                                    if ( in_array($k, $suppress) ) {
                                        continue;	
                                    }
                                    $display = false;
                                    $value = $contact->{preg_replace('/contact_/', '', $v['code'], 1)};
                                    if ( empty($value) ) {
                                        continue;	
                                    }
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
                                <div class="row">
                                    <div class="col-md-3">
                                        Type(s)
                                    </div>
                                    <div class="col-md-7">
                                       	<?php
										$types = $contact->types->lists('name', 'id');
										if ( is_array($types) && sizeof($types) > 0 ) {
											$types = implode('; ', $types);	
										} else {
											$types = '-';	
										}
										echo $types;
										?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>

<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

@stop