@extends('librarydirectory::master')

@section('content')

	</div>
	
	<link href="<?php echo url() ?>/css/new.css" rel="stylesheet">
    
	<div class="hero">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-6 intro">
                	<h1>Library Directory 2.0</h1>
                    <p>Welcome to the new Colorado Library Directory, provided by the <a href="http://www.cde.state.co.us/cdelib">Colorado State Library</a> with funding from the <a href="http://www.imls.gov/">Institute of Museum and Library Services</a>.</p>
                	<p>The <a href="http://find.coloradolibraries.org">Library Directory</a> recently had a face lift...nay...more like a complete body transplant...and we know that you will like what you see.  Keep on scrolling to learn more about the new and exciting features, or <a href="http://find.coloradolibraries.org">dive right into the data</a>.</p>
                	<p class="text-center">
                    	<a href="http://find.coloradolibraries.org/about#map" class="btn btn-custom btn-lg hero-button smooth-scroll">Learn More</a>
                        &nbsp; &nbsp; &nbsp;<a href="http://find.coloradolibraries.org" class="btn btn-default btn-lg hero-button">Go to Directory</a>
                    </p>
                </div>
            	<div class="col-md-6">
                	<img src="<?php echo url() ?>/img/framed_LD_homepage.png" class="img-responsive" alt="New Homepage">
                </div>
            </div>
        </div>
    </div>
    <div class="section a" id="map">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-6">
                	<img src="<?php echo url() ?>/img/framed_LD_filter_map.png" class="img-responsive" alt="New Homepage">
                </div>
            	<div class="col-md-6">
                	<h2><img src="<?php echo url() ?>/img/pin.png" alt="Map"> Map</h2>
                    <p>View and search Colorado libraries using the Directory's new <a href="http://find.coloradolibraries.org/map">map tool</a>. For example:  <a href="http://find.coloradolibraries.org/map?zipcode-near=80125&amp;radius=10">view all libraries within 10 miles of zipcode 80125</a>. Want to see all libraries within 5 miles of your current location?  Easy! <a href="http://find.coloradolibraries.org/map?near-me=on">Select the "Near Me" option</a>.</p>
                </div>
            </div>
        </div>
        <div class="flipped"></div>
    </div>
    <div class="section b">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-6">
                	<h2><img src="<?php echo url() ?>/img/magnifier.png" alt="Search"> Search</h2>
                    <p>Finding libraries in Colorado has never been easier thanks to the improved search feature. Filter results by keyword, library type, or location. You can also search for libraries in/around zipcodes.</p>
                </div>
            	<div class="col-md-6">
                	<img src="<?php echo url() ?>/img/framed_LD_filter_list.png" class="img-responsive" alt="New Homepage">
                </div>
            </div>
        </div>
    </div>  
    <div class="section a">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-6">
                	<img src="<?php echo url() ?>/img/framed_LD_edit_record.png" class="img-responsive" alt="New Homepage">
                </div>
            	<div class="col-md-6">
                	<h2><img src="<?php echo url() ?>/img/pencil.png" alt="Contribute"> Contribute</h2>
                    <p>The <a href="http://www.cde.state.co.us/cdelib">Colorado State Library</a> regularly feeds and waters the Directory, but we are also counting on you to help us keep the data fresh.  The old method for submitting changes (email...yuck!) has been consigned to the rubbish bin, and in its place is a new system that allows you to make direct edits to Directory data. Look for this button at the bottom of each record:</p>
                    <p><a href="#" class="btn btn-primary test-edit-this-record">Edit this Record</a></p>
                </div>
            </div>
        </div>
        <div class="flipped"></div>
    </div>
    <div class="section b thirds">
    	<div class="container">
        	<div class="row">
            	<div class="col-md-4">
					<img src="<?php echo url() ?>/img/layers.png" alt="Improved Accuracy">
                    <h2>Improved Accuracy</h2>
                    <p>Integration with Library Research Service's <a href="http://www.lrs.org/public/data/advanced">Public</a> and <a href="http://www.lrs.org/school/data/advanced">School</a> Annual Report data ensures accurate and up-to-date info.</p>
                </div>
            	<div class="col-md-4">
					<img src="<?php echo url() ?>/img/connections.png" alt="Easy Integration">
                    <h2>Easy Integration</h2>
                    <p>A <a href="http://find.coloradolibraries.org/api/v1/">powerful new API</a> makes it easy for users and developers to interact with, and <a href="http://find.coloradolibraries.org/api/v1/demo">build new services around</a>, Directory data.</p>
                </div>
            	<div class="col-md-4">
					<img src="<?php echo url() ?>/img/devices.png" alt="Mobile Friendly">
                    <h2>Mobile Friendly</h2>
                    <p>The Directory's new style adapts to a wide range of devices.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="container footer">
        <div class="row">
            <div class="col-md-12 text-center">
                <a href="http://www.cde.state.co.us/cdelib"><img src="http://www.lrs.org/images/CSLLogo-V.gif" alt="Colorado State Library"></a>
                &nbsp; &nbsp; &nbsp; &nbsp; <a href="http://www.imls.gov"><img src="http://www.lrs.org/wp-content/uploads/2013/01/IMLS_Logo_2c-Converted-300x111.jpg" alt="Institute of Museum and Library Sciences" height="60"></a>
    		</div>
        </div>
    </div>    
    <div class="modal fade" id="test-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                	<h4 class="modal-title" id="model-title">Success!</h4>
                </div>
                <div class="modal-body">
                    <p>Now go forth, edit, and help us provide accurate data!</p>
                </div>
            </div>
        </div>
    </div>   
	<script>
		$(document).ready(function() {
			// Smooth scroll to anchor
			$('.smooth-scroll').click(function() {
				var target = $(this.hash);
				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				if (target.length) {
					$('html,body').animate({
					  scrollTop: target.offset().top
					}, 1000);
					return false;
				}
			});
			// Modal for example of edit button
			$('.test-edit-this-record').click(function() {
				$('#test-modal').modal('toggle');
				return false;
			});
		});
    </script>   
	
    <div class="container" id="main-wrap">
    
@stop