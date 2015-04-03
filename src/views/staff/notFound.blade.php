@extends('librarydirectory::master')

@section('content')

<!-- staffNotFound -->

<ol class="breadcrumb">
  <li><a href="<?php echo url() ?>">Directory</a></li>
  <li><a href="<?php echo url() ?>/staff">Staff</a></li>
  <li class="active">Not Found</li>
</ol>

<div class="row">
    <div class="col-md-12">
		
        <h2>Oops! We could not find a staff record with ID #<strong><?php echo $id ?></strong></h2>
        <h3><a href="<?php echo url() ?>/staff"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the staff page</a></h3>

	</div>
</div>

@stop