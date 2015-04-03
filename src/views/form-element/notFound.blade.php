@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

    <li><a href="<?php echo url() ?>/admin/form-element">Form Elements</a></li>
    <li class="active">Not Found</li>
    
@stop

@section('adminContent')

    <h2>Oops! We could not find a form element with ID #<strong><?php echo $id ?></strong></h2>
    <h3><a href="<?php echo url() ?>/admin/form-element"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the form elements page</a></h3>

@stop