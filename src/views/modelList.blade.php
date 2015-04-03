@extends('librarydirectory::master')

@section('content')

<!-- modelList -->

<?php
if ( Input::get('organization_id') && $org = Organization::find(Input::get('organization_id')) ) {
	?>
    <ol class="breadcrumb">
        <li><a href="<?php echo url() ?>">Libraries</a></li>
        <li class="active"><?php echo $org->name ?></li>
    </ol>
    <?php
}
?>

<?php
if ( isset($data) && method_exists($data, 'getTotal') && $data->getTotal() > 0 ) {
	?>
	<div class="row quick-sort">
		<div class="col-md-12">
           	<!--<a href="#" class="btn btn-primary toggle-options pull-left">Options</a>-->
			<?php
           if ( ( Input::get('page') !== null || Input::get('keyword') !== null ) && $data->getTotal() > 0 ) {
                ?>
                <div class="col-md-3 col-md-offset-3 total-results">
                	<div class="pull-left"><strong><?php echo number_format($data->getTotal()) ?></strong> Results</div>
                </div>
				<?php
            }
			echo $quickSort->querystring($querystring)->sortable($sortable);
			?>
		</div>
	</div>  
    <!-- /quick sorting -->
	<?php
}
?>

<div class="row model-list">
	<div class="col-md-3 refine">
		<?php echo $searchForm ?>
    </div><!-- search form -->
    <div class="col-md-9 results">
		<?php displayFlashMessages($errors) ?>
        
        @yield('table')
        
    </div><!-- /main data table -->
</div>

<?php
if ( isset($data) && method_exists($data, 'getTotal') ) {
	?>
    <div class="row">
        <div class="col-md-12 pagination-wrap">
            <?php echo $data->links() ?>
        </div>
    </div><!-- /pagination -->
	<?php
}

if ( Auth::check() ) {
	?>
    <div class="modal fade" id="edit-staff-modal" role="dialog" aria-labelledby="model-title" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
    
            </div>
        </div>
    </div><!-- / misc modal -->
    <?php	
}
?>

@stop