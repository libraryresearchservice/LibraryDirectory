@extends('librarydirectory::admin')

@section('adminBreadcrumbs')

    <li><a href="<?php echo url() ?>/admin/contact-type">Contact Types</a></li>
    <li class="active">Not Found</li>
    
@stop

@section('adminContent')

    <h2>Oops! We could not find a contact type with ID #<strong><?php echo $id ?></strong></h2>
    <h3><a href="<?php echo url() ?>/admin/contact-type"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the contact-types page</a></h3>

@stop