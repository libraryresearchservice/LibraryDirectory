@extends('librarydirectory::modelList')

@section('table')

<!-- library/all -->

<?php
if ( $data->getTotal() > 0 ) {
	?>
	<div class="table-responsive">
        <table class="table table-striped directory-list">
            <thead>
                <tr>
                    <th>Organization<?php echo userCanEdit() ? ' &nbsp;<a class="staff-modal" href="'.url().'/admin/organization/create"><i class="fa fa-plus-circle"></i></a>' : '' ?></th>
                    <th>Library<?php echo userCanEdit() ? ' &nbsp;<a href="'.url().'/library/create"><i class="fa fa-plus-circle"></i></a>' : '' ?></th>
                    <th>Type</th>
                    <th>City</th>
                    <th>County</th>
                    <th>More Info</th>
                    <?php echo userCanEdit() ? '<th>Edit</th>' : '' ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $data as $v ) {
                    $geo = sizeof($v->geo) > 0;
                    $view = '<a href="'.url().'/library/view/'.$v->id.'">';
                    ?>
                    <tr>
                        <td><?php echo $view ?><?php echo $v->organization_name.($v->organization_alt_name ? ' ('.$v->organization_alt_name.')' : '') ?></a></td>
                        <td><?php echo $view ?><?php echo ($v->is_publicly_visible != 1 ? '<i class="fa fa-lock"></i> ' : '').($v->name === null ? '-' : $v->name) ?></a></td>
                        <td><?php echo $view ?><?php echo $v->library_type_name !== null ? $v->library_type_name : '-' ?></td>
                        <td><?php echo $view ?><?php echo $v->city !== null ? $v->city : '-' ?></a></td>
                        <td><?php echo $view ?><?php echo $v->county !== null ? $v->county : '-' ?></a></td>
                        <td class="text-center"><?php echo $view ?><i class="fa fa-file-text-o"></i></a></td>
                        <?php echo userCanEdit() ? '<td><a href="'.url().'/library/edit/'.$v->id.'"><i class="fa fa-pencil-square-o"></i></a></td>' : '' ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
	<p class="text-right">
	<?php
	$qString = trimQuerystring();
	if ( querystringIsMappable() ) {
		?>
        <a href="<?php echo url().'/map'.($qString != '' ? '?'.$qString : '') ?>" class="btn btn-info">Switch to Map View</a>&nbsp; &nbsp; &nbsp;
        <?php	
	}
	if ( !Auth::check() ) {
		?>
		<a href="http://find.coloradolibraries.org/library/public-edit" class="btn btn-default">Add Your Library</a>&nbsp; &nbsp; &nbsp;
		<?php
	}
	if ( Auth::check() ) {
		?>
		<a href="<?php echo url().'/api/library/csv'.($qString != '' ? '?'.$qString : '') ?>" class="btn btn-info">Export</a>
		<br />
		<?php
	}
	?>
    </p>
    <?php
} else {
	?>
	<h2>No libraries match your criteria</h2>
	<?php	
}
?>

@stop