@extends('librarydirectory::master')

@section('content')

<!-- pageNotFound -->

<ol class="breadcrumb">
	<li><a href="<?php echo url() ?>">Directory</a></li>
	<li><a href="<?php echo url() ?>/api">API</a></li>
    <li class="active">Demo</li>
</ol>

<script>
$(document).ready(function() {
	// Capture form submission
	$('#api-demo').submit(function() {
		var keyword = $('#keyword').val().trim();
		// Basic validation
		if ( keyword == '' ) {
			alert('You must enter a keyword!');
			return false;	
		}
		if ( keyword.length < 4 ) {
			alert('You must enter more than 3 characters!');
			return false;	
		}
		// Make GET request to Library Directory API. The exact method you use may vary. If using JavaScript, be sure
		// to understand cross domain requests (hint: JSONP)!
		$.getJSON('http://find.coloradolibraries.org/api/v1/library?' + $('#api-demo').serialize(), function(data) {
			// Scroll to the top of the results section
			$('.results').scrollTop(0);
			$('#demo-table').find('tbody').html(' ');
			// Iterate results
			for ( var i = 0; i < data.length; i++ ) {
				// Format HTML
				var record = '<p><strong>Library</strong>: <a href="http://find.coloradolibraries.org/library/view/' + data[i]['id'] + '">' + data[i]['name'] + '</a><br /><strong>Organization</strong>: ' + data[i]['organization_name'] + '<br /><strong>City</strong>: ' + data[i]['mail_city'] + '<br /><strong>County</strong>: ' + data[i]['county'] + '</p>';
				// Append HTML to page
				$('#demo-table').find('tbody')
					.append($('<tr>')
						.append( $('<td>').append(data[i]['id']) )
						.append( $('<td>').append('<a href="http://find.coloradolibraries.org/library/view/' + data[i]['id'] + '">' + data[i]['name'] + '</a>') )
						.append( $('<td>').append(data[i]['organization_name']) )
						.append( $('<td>').append(data[i]['mail_city']) )
						.append( $('<td>').append(data[i]['county']) )
					);
			}
		});
		return false;
	});
});
</script>
        
<div class="row">
    <div class="col-md-12">
        
        <h2>Library Directory API Demo App</h2>
        <p>The following is a simple app that uses JQuery to query the Library Directory by keyword and display results in a list.</p>
    	<br />
        <form class="form-inline" id="api-demo">
        	<div class="form-group">
            	<label for="keyword">Keyword </label>
                <input class="form-control" type="text" id="keyword" name="keyword" value="">
            </div>
            <input class="btn btn-primary" type="submit" value="Find"></p>
        </form>
        <br />
        <div class="results">
			<table class="table" id="demo-table">
            	<thead>
                	<tr>
                    	<th>ID</th>
                        <th>Name</th>
                        <th>Organization</th>
                        <th>City</th>
                        <th>County</th>
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
            </table>
        </div>
        
	</div>
</div>

@stop