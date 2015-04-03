@extends('librarydirectory::master')

@section('content')

<!-- libraryNotFound -->

<ol class="breadcrumb">
  <li><a href="<?php echo url() ?>">Directory</a></li>
  <li class="active">Library Not Found</li>
</ol>

<div class="row">
    <div class="col-md-12">
		
        <h2>Oops! We could not find a library with ID #<strong><?php echo $id ?></strong></h2>
        <h3><a href="<?php echo url() ?>"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the home page</a></h3>

	</div>
</div>

@stop