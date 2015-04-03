@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

    <li><a href="<?php echo url() ?>/admin/user">Users</a></li>
	<li class="active">Not Found</li>
    
@stop

@section('adminContent')

<h2>Oops! We could not find a user with ID #<strong><?php echo $id ?></strong></h2>
<h3><a href="<?php echo url() ?>/admin/user"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the users page</a></h3>

@stop