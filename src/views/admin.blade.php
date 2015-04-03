<?php $action = Request::segment(2) ?>

@extends('librarydirectory::master')

@section('content')

<div class="row">
	<div class="col-md-3">
        <ul class="nav nav-pills nav-stacked admin-nav">
            <?php
			if ( userIsAdmin() ) {
				?>
                <li<?php echo $action == 'contact-type' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/contact-type">Contact Types</a></li>
                <li<?php echo $action == 'form-element' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/form-element">Library/Staff Edit Form Elements</a></li>
                <li<?php echo $action == 'library-type' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/library-type">Library Types</a></li>
                <li<?php echo $action == 'columns' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/columns">List Table Columns</a></li>
                <li<?php echo $action == 'organization' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/organization">Organizations</a></li>
                <li<?php echo $action == 'public-edits' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/public-edits">Public Edits</a></li>
                <li<?php echo $action == 'update-geolocation-data' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/update-geolocation-data">Update Geolocation Data</a></li>
                <?php
			}
			?>
        	<li<?php echo $action == 'user' ? ' class="active"' : '' ?>><a href="<?php echo url() ?>/admin/user">Users</a></li>
        </ul>
    </div>
    <div class="col-md-9">
    
		<?php displayFlashMessages($errors) ?>
        
    	@yield('adminContent')
    
    </div>
</div>

@stop