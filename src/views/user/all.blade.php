@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Users</li>
    
@stop

@section('adminContent')

<?php echo $quickSort->sortable($sortable) ?>

<table class="table table-striped directory-list">
	<thead>
    	<tr>
        	<th>Name  &nbsp;<a href="<?php echo url() ?>/admin/user/create"><i class="fa fa-plus-circle"></i></a></th>
            <th>ID</th>
            <th>Username</th>
            <th>Level</th>
            <th>Unit</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
    	<?php
		foreach ( $data as $v ) {
			?>
            <tr>
            	<td><?php echo $v->name ?></td>
                <td><?php echo $v->id ?></td>
                <td><?php echo $v->username ?></td>
                <td><?php echo ucwords($v->level) ?></td>
                <td><?php echo $v->office ?></td>
                <td><a href="<?php echo url() ?>/admin/user/edit/<?php echo $v->id ?>"><i class="fa fa-pencil-square-o"></i></a></td>
            </tr>
            <?php
		}
		?>
    </tbody>
</table>

@stop