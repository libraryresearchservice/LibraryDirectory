@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

    <li><a href="<?php echo url() ?>/admin/organization">Organizations</a></li>
    <li class="active">Not Found</li>
    
@stop

@section('adminContent')

    <h2>Oops! We could not find an organization with ID #<strong><?php echo $id ?></strong></h2>
    <h3><a href="<?php echo url() ?>/admin/organization"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the form organizations page</a></h3>

@stop