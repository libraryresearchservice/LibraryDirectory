@extends('librarydirectory::master')

@section('content')

<!-- pageNotFound -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>">Directory</a></li>
	<li class="active">Contact Not Found</li>
</ol>

<div class="row">
    <div class="col-md-12">
		
        <h2>Oops! The contact you are looking for does not exist.</h2>
        <p>The Library Directory is frequently updated and it is possible that the record you are looking for has changed or no longer exists.</p>
        <p>Use the form below to search by keyword.</p>
		<?php echo $searchForm ?>
        <h3><a href="<?php echo url() ?>"><span class="glyphicon glyphicon-arrow-left"></span> Mosey on back to the home page</a></h3>

	</div>
</div>


@stop