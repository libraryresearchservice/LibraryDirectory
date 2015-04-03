@extends('librarydirectory::master')

@section('content')

<!-- editLibrary -->

<ol class="breadcrumb">
    <li><a href="<?php echo url() ?>">Libraries</a></li>
</ol>

<div class="row">
    <div class="col-md-12">
		<h1>SPAM</h1>
        <p>Oops! Looks like the Library Directory thinks you are trying to submit SPAM.</p>
        <p>Are you an actual human?  If so, this is most likely the result of an outdated browser.  In order to submit changes to Library Directory data you must use a modern browser and have Javascript enabled.</p>
		<p>For further assistance please contact the Colorado State Library at 303-866-6900. Keep the following info handy as it will help us troubleshoot:</p>
    	<ul>
        	<li>IP Address: <?php echo Request::server('REMOTE_ADDR') ?></li>
            <li>User agent: <?php echo Request::server('HTTP_USER_AGENT') ?></li>
        </ul>
    </div>
</div>

@stop