@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Form Elements</li>
    
@stop

@section('adminContent')

<div class="alert alert-warning">
	<p>There is no function to create form elements because in addition to creating columns in the database you must also add the new column name to /functions/columns.php.</p>
</div>

<?php
$before = '<select name="form_name" class="form-control">';
	$before .=  '<option value="">All</option>';
	foreach ( $formNames as $v ) {
		$before .= '<option value="'.$v.'"'.(Input::get('form_name') == $v ? ' selected' : '').'>'.ucwords($v).'</option>';
	}
$before .= '</select>';
echo $quickSort->before($before)->sortable($sortable);
?>

<table class="table table-striped directory-list">
	<thead>
    	<tr>
            <th>Name</th>
            <th>ID</th>
            <th>Column</th>
            <th>Form</th>
            <th>Hidden</th>
            <th>Active</th>
            <th>Publicly Editable</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		foreach ( $elements as $v ) {
			?>
            <tr>
                <td><?php echo $v->name ?></td>
                <td><?php echo $v->id ?></td>
                <td><?php echo $v->code ?></td>
                <td><?php echo ucwords($v->form_name) ?></td>
                <td><?php echo $v->is_hidden ? '<i class="fa fa-check"></i>' : '' ?></td>
                <td><?php echo $v->is_active ? '<i class="fa fa-check"></i>' : '' ?></td>
                <td><?php echo $v->is_public ? '<i class="fa fa-check"></i>' : '' ?></td>
                <td><a href="<?php echo url() ?>/admin/form-element/edit/<?php echo $v->id ?>"><i class="fa fa-pencil-square-o"></i></a></td>
            </tr>
            <?php
		}
		?>
    </tbody>
</table>

@stop