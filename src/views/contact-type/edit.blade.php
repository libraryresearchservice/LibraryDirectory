@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

	<li><a href="<?php echo url() ?>/admin/contact-type">Contact Types</a></li>
	<li class="active">Edit Contact Type</li>
    
@stop

@section('adminContent')

<form class="form-horizontal" id="edit-staff" action="<?php echo url() ?>/admin/contact-type/save" method="post" role="form">
    <input type="hidden" name="id" value="<?php echo $data->id ?>">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo $data->name ?>">
        </div>
        <div class="form-group">
            <label for="name">Order</label>
            <input type="text" name="type_order" id="type_order" class="form-control" value="<?php echo $data->type_order ?>">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Save">
            <input type="submit" class="btn btn-default pull-right" name="delete" value="Delete">
        </div>
    </div>   
</form>

@stop