@extends('librarydirectory::modelList')

@section('table')

<!-- map -->

<link href="<?php echo url() ?>/css/map.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script type="text/javascript">
	var base = '<?php echo url() ?>';
	var latitude = <?php echo $latitude ? $latitude : 'false' ?>;
	var longitude = <?php echo $longitude ? $longitude : 'false' ?>;
	var nearMe = <?php echo $nearMe ? "'".$nearMe."'" : 'false' ?>;
	var radius = <?php echo $radius > 0 ? $radius : 'false' ?>;
</script>
<!-- Include map script after coordinates (if user submits a zip) are calculated -->
<script src="<?php echo url() ?>/js/map.js"></script> 

<div id="map-canvas-wrap">
    <div id="map-canvas"></div><!-- /map-canvas -->
</div><!-- /map-canvas-wrap -->
<p class="text-right" id="map-total">&nbsp;</p>

@stop