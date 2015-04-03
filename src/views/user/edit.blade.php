@extends('librarydirectory::admin')

@section('adminBreadcrumbs')
	
    <li><a href="<?php echo url() ?>/admin/user">Users</a></li>
	<li class="active">Edit <?php echo $user->name ?></li>
    
@stop

@section('adminContent')

<form class="form-horizontal" id="edit-user" action="<?php echo url() ?>/admin/user/save" method="post" role="form">  
    <input type="hidden" name="id" value="<?php echo $user->id ?>">
    <div class="form-group">
        <label class="col-md-2" for="name">Name</label>
        <div class="col-md-3">
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $user->name ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2" for="username">Username</label>
        <div class="col-md-3">
            <input type="text" class="form-control" name="username" id="username" value="<?php echo $user->username ?>"> 
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2" for="email">Email</label>
        <div class="col-md-3">
            <input type="text" class="form-control" name="email" id="email" value="<?php echo $user->email ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2" for="office">Unit</label>
        <div class="col-md-5">
            <input type="text" class="form-control" name="office" id="office" value="<?php echo $user->office ?>">
        </div>
    </div>
    <?php
	if ( userIsAdmin() ) {
		?>
        <div class="form-group">
            <label class="col-md-2" for="level">Level</label>
            <div class="col-md-4">
                <select name="level" id="level" class="form-control"<?php echo !userIsAdmin() ? ' disabled' : '' ?>>
                <?php
                foreach ( userLevels() as $v ) {
                    ?>
                    <option value="<?php echo $v ?>"<?php echo $user->level == $v ? ' selected' : '' ?>><?php echo ucwords($v) ?></option>
                    <?php
                }
                ?>
                </select><?php echo !userIsAdmin() ? ' * Only an admin can change your level' : '' ?>
            </div>
        </div>
        <?php
	}
	?>
    <div class="form-group">
        <label class="col-md-2" for="edit_notifications">Notify of Public Edits</label>
        <div class="col-md-2">
            <select name="edit_notifications" id="edit_notifications" class="form-control">
            <?php
            foreach ( array(0 => 'No', 1 => 'Yes') as $k => $v ) {
                ?>
                <option value="<?php echo $k ?>"<?php echo $user->edit_notifications == $k ? ' selected' : '' ?>><?php echo $v ?></option>
                <?php
            }
            ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2" for="name">Password</label>
        <div class="col-md-3">
            <input type="password" class="form-control" name="password" id="name">
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-2" for="name">Password (confirm)</label>
        <div class="col-md-3">
            <input type="password" class="form-control" name="password_confirm" id="name">
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-6">
            <input type="submit" class="btn btn-primary" value="Save">
            <?php
            if ( $user->id ) {
                ?>
                <input type="submit" name="delete" class="btn btn-default btn-sm pull-right" value="Delete">
                <?php	
            }
            ?>
        </div>
    </div>
</form>

@stop