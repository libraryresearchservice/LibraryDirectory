@extends('librarydirectory::master')

@section('content')

<!-- pageNotFound -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>">Directory</a></li>
	<li class="active">API</li>
</ol>

<div class="row">
    <div class="col-md-12">
		
        <h1>Library Directory API</h1>
        
        <h2>About</h2>
        <p>This API returns data from the Library Directory in JSON format.</p>
        
        <h2>URL</h2>
        <p>Send a GET request to <a href="http://find.coloradolibraries.org/api/v1/library">http://find.coloradolibraries.org/api/v1/library</a></p>
        
        <h2>Parameters</h2>
        <table class="table">
        	<tr>
            	<th>Field</th>
                <th>Description</th>
                <th>Value(s)</th>
            </tr>
            <tr>
            	<td>keyword</td>
            	<td>Keyword search by library name, organization name, city, county, and zipcode.</td>
                <td>Alphanumeric string</td>
            </tr>
            <tr>
            	<td>library-type[]</td>
            	<td>Type of library.</td>
                <td>
                	<p>One or more of the following numeric IDs:</p>
					<ul>
					<?php
					foreach ( libraryTypes() as $k => $v ) {
						?>
                        <li><?php echo $v['id'] ?> (<?php echo $v['name'] ?>)</li>
                        <?php
					}
					?>
                    </ul>
                </td>
            </tr>
            <tr>
            	<td>city</td>
            	<td>Keyword search by city.</td>
                <td>Alphanumeric string</td>
            </tr>
            <tr>
            	<td>county</td>
            	<td>Keyword search by county.</td>
                <td>Alphanumeric string</td>
            </tr>
            <tr>
            	<td>zipcode-near</td>
            	<td>Results at or near a zipcode.</td>
                <td>5 digit integer</td>
            </tr>
            <tr>
            	<td>near-me</td>
            	<td>Results near the geolocation of the person or server making the request.</td>
                <td>1</td>
            </tr>
            <tr>
            	<td>limit</td>
            	<td>Specify the number of results to return.</td>
                <td>Integer</td>
            </tr>
        </table>
        <script>
		$(document).ready(function() {
			$('.api-example-link').click(function() {
				var thisURL = $(this).attr('href');
				$.get( thisURL, function( data ) {
					$('.results').scrollTop(0);
					var dataString = JSON.stringify(data, null, 4);
					$('.results p div').remove();
					$('.results').html('<p><pre>' + dataString + '</pre></p>');
				});
				return false;
			});
		});
		</script>
        <h2>Results</h2>
        <p>Results are returned in JSON format.  Click the links below for examples.
        <p><strong>All public and academic libraries</strong></p>
        <p><a class="api-example-link" href="http://find.coloradolibraries.org/api/v1/library?keyword=&library-type[]=1&library-type[]=2&limit=5">http://find.coloradolibraries.org/api/v1/library?keyword=&library-type[]=1&library-type[]=2</a></p>
        <p><strong>Results within 5 miles of zipcode 80203</strong></p>
        <p><a class="api-example-link" href="http://find.coloradolibraries.org/api/v1/library?keyword=&city=&county=&zipcode-near=80203&radius=5&limit=5">http://find.coloradolibraries.org/api/v1/library?keyword=&city=&county=&zipcode-near=80203&radius=5</a></p>
        
        <div class="panel panel-default">
        	<div class="panel-heading">Results</div>
            <div class="panel-body results"><p>Select an example from above</p></div>
        </div>
        
        <h2>Demo</h2>
		<a class="btn btn-primary btn-lg" href="http://find.coloradolibraries.org/api/v1/demo">View Demo</a>
        
	</div>
</div>


@stop