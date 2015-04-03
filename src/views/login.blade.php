@extends('librarydirectory::master')

@section('content')

<!-- login -->

<?php displayFlashMessages($errors) ?>

<form class="form-signin" method="post" action="<?php echo url() ?>/login">       
    <div class="row">
        <div class="form-group col-md-4">
            <h2>Sign in</h2>
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-user"></i></div>
                <input class="form-control" type="text" id="username" name="username" placeholder="Username">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-lock"></i></div>
                <input class="form-control" type="password" id="password" name="password" placeholder="Password">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-2">
			<input type="submit" class="btn btn-large btn-primary" value="Sign In">
        </div>
        <div class="form-group col-md-2 text-right">
        	<a href="<?php echo url() ?>/doh" class="btn btn-large">Forget?</a>
        </div>
    </div>
    
</form>

@stop