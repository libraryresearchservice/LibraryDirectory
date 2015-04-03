@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

    <li><a href="<?php echo url() ?>/admin/library-type">Library Types</a></li>
    <li class="active">Not Found</li>
    
@stop

@section('adminContent')

    <h2>Oops! We could not find a library type with ID #<strong><?php echo $id ?></strong></h2>
    <h3><a href="<?php echo url() ?>/admin/library-type"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the library-types page</a></h3>

@stop