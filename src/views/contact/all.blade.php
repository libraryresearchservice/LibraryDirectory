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
                    <th>Contact<?php echo userCanEdit() ? ' &nbsp;<a href="'.url().'/contact/create"><i class="fa fa-plus-circle"></i></a>' : '' ?></th>
                    <th>Title</th>
                    <th>Organization<?php echo userCanEdit() ? ' &nbsp;<a class="staff-modal" href="'.url().'/admin/organization/create"><i class="fa fa-plus-circle"></i></a>' : '' ?></th>
                    <th>Type</th>
                    <th>More Info</th>
                    <?php echo userCanEdit() ? '<th>Edit</th>' : '' ?>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ( $data as $v ) {
                    $view = '<a href="'.url().'/contact/view/'.$v->id.'">';
                    ?>
                    <tr>
                        <td><?php echo $view ?><?php echo $v->name ?></a></td>
                        <td><?php echo $view ?><?php echo $v->title ?></a></td>
                        <td><?php echo $view ?><?php echo $v->organization_name.($v->organization_alt_name ? ' ('.$v->organization_alt_name.')' : '') ?></a></td>
                        <td><?php echo $view ?><?php echo $v->contact_type_names ?></a></td>
                        <td class="text-center"><?php echo $view ?><i class="fa fa-file-text-o"></i></a></td>
                        <?php echo userCanEdit() ? '<td><a href="'.url().'/contact/edit/'.$v->id.'"><i class="fa fa-pencil-square-o"></i></a></td>' : '' ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
	<?php
	if ( Auth::check() ) {
		$qString = trimQuerystring();
		?>
		<p class="text-right"><a href="<?php echo url().'/api/contact/csv'.($qString != '' ? '?'.$qString : '') ?>" class="btn btn-info">Export</a></p>
		<br />
		<?php
	}
} else {
	?>
	<h2>No libraries match your criteria</h2>
	<?php	
}
?>

@stop