@extends('librarydirectory::master')

@section('content')

<!-- pageNotFound -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>">Directory</a></li>
	<li class="active">Page Not Found</li>
</ol>

<div class="row">
    <div class="col-md-12">
		
        <h2>Oops! The page you are looking for does not exist.</h2>
        <h3><a href="<?php echo url() ?>"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the home page</a></h3>

	</div>
</div>


@stop