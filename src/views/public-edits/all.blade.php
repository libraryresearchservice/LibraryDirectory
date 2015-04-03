@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Public Edits</li>
    
@stop

@section('adminContent')

<!-- user-edits/all -->
<style type="text/css">
	.public-edit .current-data {
		background-color: #eee;	
	}
	.public-edit-wrap {
		overflow: auto;
	}
	.public-edit-wrap .public-edit-date {
		min-width: 15%;
	}
</style>
<div class="col-md-12 public-edit-wrap">
    <?php
	if ( sizeof($edits) > 0 ) {
		?>
        <table class="table directory-list public-edit">
            <tr>
                <th>Date</th>
                <th>Edit ID</th>
                <th>Library</th>
                <th>Message</th>
                <th>View</th>
                <th>Delete</th>
            </tr>
            <?php
			foreach ( $edits as $v ) {
				$class = false;
				$id = false;
				$library = false;
				if ( isset($v->library->id) ) {
					$id = $v->library->id;
				} else {
					$class = 'alert-info';
				}
				if ( $id && isset($v->library->name) ) {
					$library = $v->library->name;
				} else if ( $v->name != '' ) {
					$library = $v->name;	
				}
				?>
                <tr>
                    <td class="public-edit-date <?php echo $class ?>">
					<?php echo date('M jS Y', strtotime($v->created_at)) ?><br />
                    <?php echo date('g:i a', strtotime($v->created_at)) ?>
                    </td>
                    <td class="<?php echo $class ?>"><?php echo $v->edit_id ?></td>
                    <td class="<?php echo $class ?>"><?php echo $library ?></td>
                    <td class="<?php echo $class ?>"><?php echo $v->edit_message ?></td>
                    <?php
					if ( $id ) {
						?>
                    	<td class="text-center "><a class="open-new" href="<?php echo url() ?>/library/edit/<?php echo $id ?>?with-public=<?php echo $v->edit_id ?>"><i class="fa fa-file-text-o"></i></a></td>
                		<?php
					} else {
						?>
                        <td class="text-center <?php echo $class ?>">
                        	<a class="open-new" href="<?php echo url() ?>/library/create/?with-public=<?php echo $v->edit_id ?>"><i class="fa fa-file-text-o"></i></a>
                       	</td>
                        <?php
					}
					?>
                    <td class="text-center <?php echo $class ?>">
                    	<a href="<?php echo url() ?>/admin/public-edits/delete/<?php echo $v->edit_id ?>"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
                <?php
			}
            ?>
        </table>
    	<?php
	} else {
		?>
        <div class="alert alert-success keep-open">Hooray! There are no public edits.</div>
        <?php	
	}
	?>
</div>
        
@stop