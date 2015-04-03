@extends('librarydirectory::master')

@section('content')

<!-- login -->

<?php displayFlashMessages($errors) ?>

<p>Forget your password?  Hey, it happens to the best of us. Tell us your email and, if you have an account, a new password will be sent to you.</p>
<form method="post">
    <div class="row">
        <div class="form-group col-md-4">
            <div class="input-group">
                <div class="input-group-addon"><i class="fa fa-envelope-o"></i></div>
                <input class="form-control" type="text" id="email" name="email" placeholder="Email">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-4">
			<input type="submit" class="btn btn-large btn-primary" value="Reset Password">
        </div>
    </div>
</form>
@stop