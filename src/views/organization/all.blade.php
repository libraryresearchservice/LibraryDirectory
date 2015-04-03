@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li class="active">Organizations</li>
    
@stop

@section('adminContent')

<form class="navbar-form navbar-left" id="keyword-inline" role="form">
	<div class="row">
    	<input class="form-control" name="keyword" placeholder="Search by Keyword" size="40" value="<?php echo Input::get('keyword', false) ?>">
    	<input class="btn btn-default" type="submit" value="Search">
    </div>
</form>
<?php echo $quickSort->sortable($sortable) ?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-striped directory-list">
            <thead>
                <tr>
                    <th>Organization <a class="staff-modal" href="<?php echo url() ?>/admin/organization/create"><i class="fa fa-plus-circle"></i></a></th>
                    <th>ID</th>
                    <th>Alternate Name</th>
                    <th>Description</th>
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
                        <td><?php echo $v->alt_name ? $v->alt_name : '-' ?></td>
                        <td><?php echo $v->description ? $v->description : '-' ?></td>
                        <td><a class="staff-modal" href="<?php echo url() ?>/admin/organization/edit/<?php echo $v->id ?>"><i class="fa fa-pencil-square-o"></i></a></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
	</div>
</div>

<div class="row">
    <div class="col-md-12 pagination-wrap">
    	<?php echo $data->links() ?>
    </div>
</div>

<div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

        </div>
    </div>
</div>

@stop