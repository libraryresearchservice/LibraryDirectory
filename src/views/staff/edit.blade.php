@extends('librarydirectory::master')

@section('content')

<!-- editStaff -->

<ol class="breadcrumb">
    <li><a href="<?php echo url() ?>/staff">Staff</a></li>
    <li><a href="<?php echo url() ?>/staff?organization_id=<?php echo $staff->organization_id ?>"><?php echo $staff->organization_name ?></a></li>
	<li><a href="<?php echo url() ?>/staff?library_id=<?php echo $staff->libid ?>"><?php echo $staff->libranch ?></a></li>
    <li><a href="<?php echo url() ?>/staff/view/<?php echo $staff->staffid ?>"><?php echo $staff->pername ? ucwords(strtolower($staff->pername)) : '<em>No Name</em>' ?></a></li>
    <li class="active">Edit</li>
</ol>

<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" id="edit-staff" action="<?php echo url() ?>/staff/save" method="post" role="form">
            <input type="hidden" name="return-to" value="<?php echo Input::get('return-to', false) ?>">
            <div class="staff-edit-wrap col-md-4">
                <div class="panel panel-default">
                      <div class="panel-heading"></div>
                      <div class="panel-body">
                        <?php
                        foreach ( $staffElements as $k => $v ) {
                            if ( $v['is_hidden'] == 1 ) {
                                ?>
                                <input type="hidden" name="<?php echo $v['code'] ?>" value="<?php echo $staff->{strtolower($v['code'])} ?>" />
                                <?php
                            }
                        }
						?>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="libinst">Organization</label>
                                <input type="text" id="libinst" class="form-control" value="<?php echo $staff->organization_name ?>" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label for="libranch">Library</label>
                                <input type="text" id="libranch" class="form-control" value="<?php echo $staff->libranch ?>" disabled>
                            </div>
                        </div>
                        <?php
                        foreach ( $staffElements as $k => $v ) {
                            if ( $v['is_hidden'] == 1 || $v['is_active'] == 0 ) {
                                continue;
                            }
                            ?>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="<?php echo $v['code'] ?>"><?php echo $v['name'] != '' ? $v['name'] : $v['code'] ?></label>
                                    <?php
                                    if ( $v['data_type'] == 'text' ) {
                                        ?>
                                        <input type="text" class="form-control" name="<?php echo $v['code'] ?>" id="<?php echo $v['code'] ?>" value="<?php echo $staff->{strtolower($v['code'])} ?>">
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="staff-edit-wrap col-md-12">
            	<input type="submit" class="btn btn-primary" value="Save">
        	</div>
        </form>
        
	</div>
</div>

@stop