@extends('librarydirectory::modelList')

@section('table')

<!-- staff/all -->

<?php
if ( $data->getTotal() > 0 ) {
	$types = libraryCodes();
	?>
	<table class="table table-striped directory-list">
		<thead>
			<tr>
				<th>Name</th>
				<th>Title</th>
				<th>Organization</th>
				<th>Library</th>
				<th>Type</th>
				<th>More Info</th>
				<?php echo Auth::check() ? '<th>Edit</th>' : '' ?>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $data as $v ) {
				$view = '<a href="'.url().'/staff/view/'.$v->staffid.'">';
				?>
				<tr>
					<td><?php echo $view ?><?php echo $v->pername === null ? '-' : ucwords(strtolower($v->pername)) ?></a></td>
					<td><?php echo $view ?><?php echo $v->pertitle === null ? '-' : ucwords(strtolower($v->pertitle)) ?></a></td>
					<td><?php echo $view ?><?php echo $v->organization_name.($v->organization_alt_name ? ' ('.$v->organization_alt_name.')' : '') ?></a></td>
					<td><?php echo $view ?><?php echo $v->libranch === null ? '-' : ucwords(strtolower($v->libranch)) ?></a></td>
					<td><?php echo isset($types[substr($v->type, 0, 2)]) ? $types[substr($v->type, 0, 2)] : '-' ?></td>
					<td class="text-center"><?php echo $view ?><i class="fa fa-file-text-o"></i></a></td>
					<?php echo Auth::check() ? '<td><a class="staff-modal" rel="'.$v->staffid.'" href="'.url().'/staff/edit/'.$v->staffid.'"><i class="fa fa-pencil-square-o"></i></a></td>' : '' ?>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
	if ( Auth::check() ) {
		/*
		?>
		<a href="<?php echo url().'/api/staff/csv'.(Request::server('QUERY_STRING') ? '?'.Request::server('QUERY_STRING') : '') ?>" class="btn btn-info pull-right">Export .csv</a>
		<?php
		*/
	}
} else {
	?>
	<h2>No staff match your criteria</h2>
	<?php	
}
?>

@stop