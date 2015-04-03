@extends('librarydirectory::master')

@section('content')

<!-- staff -->

<ol class="breadcrumb">
	<!--<li><a href="<?php echo url() ?>">Directory</a></li>-->
    <li><a href="<?php echo url() ?>/staff">Staff</a></li>
    <li><a href="<?php echo url() ?>/staff?organization_id=<?php echo $staff->organization_id ?>"><?php echo $staff->organization_name ?></a></li>
	<li><a href="<?php echo url() ?>/staff?library_id=<?php echo $staff->libid ?>"><?php echo $staff->libranch ?></a></li>
    <li class="active"><?php echo ucwords(strtolower($staff->pername)) ?></li>
</ol>

<?php displayFlashMessages($errors) ?>

<div class="library-meta">
	<?php
    foreach ( $staffElements as $v ) {
        if ( $v['is_active'] && !$v['is_hidden'] && $staff->{strtolower($v['code'])} ) {
            ?>
            <div class="row">
                <div class="col-md-2">
                    <?php echo $v['name'] ?>
                </div>
                <div class="col-md-10">
                    <?php echo $staff->{strtolower($v['code'])} ?>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <div class="row">
        <div class="col-md-2">
            Institution
        </div>
        <div class="col-md-10">
            <a href="<?php echo url() ?>/library?organization_id=<?php echo $staff->library->organization_id ?>" class="open-new"><?php echo $staff->organization_name.($staff->organization_alt_name ? ' ('.$staff->organization_alt_name.')' : '') ?> <i class="fa fa-file-text-o"></i></a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            Branch
        </div>
        <div class="col-md-10">
            <a href="<?php echo url() ?>/library/view/<?php echo $staff->library->libid ?>" class="open-new"><?php echo $staff->library->libranch ?> <i class="fa fa-file-text-o"></i></a>
        </div>
    </div>
    <?php
	if ( Auth::check() ) {
		?>
        <div class="row">
            <div class="col-md-12">
                <a href="<?php echo url() ?>/staff/edit/<?php echo $staff->staffid ?>" class="btn btn-primary">Edit</a>
            </div>
        </div>
        <?php
	}
	?>
</div>

@stop