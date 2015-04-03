@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Library Types</li>
    
@stop

@section('adminContent')

<table class="table table-striped directory-list">
	<thead>
    	<tr>
            <th>Library Type <a href="<?php echo url() ?>/admin/library-type/create"><i class="fa fa-plus-circle"></i></a></th>
            <th>ID</th>
            <th>Parent</th>
            <th>Order</th>
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
                <td><?php echo $v->parent_id > 0 ? $data->find($v->parent_id)->name : '-' ?></td>
                <td><?php echo $v->type_order ?></td>
                <td><a href="<?php echo url() ?>/admin/library-type/edit/<?php echo $v->id ?>"><i class="fa fa-pencil-square-o"></i></a></td>
            </tr>
            <?php
		}
		?>
    </tbody>
</table>

@stop