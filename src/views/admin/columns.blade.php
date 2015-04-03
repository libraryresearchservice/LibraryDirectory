@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Table Columns</li>
    
@stop

@section('adminContent')

<h2><?php echo $table ?></h2>

<?php echo $quickSort->sortable($sortable) ?>

<table class="table table-striped">
	<tr>
    	<th>Name</th>
    </tr>
	<?php
	foreach ( $columns as $v ) {
		?>
        <tr>
        	<td><?php echo $v ?></td>
        </tr>
		<?php	
	}
	?>
</table>

@stop