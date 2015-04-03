function disableZipAndRadius() {
	var nearMeChecked = $('#near-me').is(':checked');
	if ( nearMeChecked ) {
		$('#zipcode-near').val('').attr('disabled', true);
		$('#radius').val('').attr('disabled', true);
	} else {
		$('#zipcode-near').attr('disabled', false);
		$('#radius').attr('disabled', false);
	}
}
function toggleFilterFormCoordinates(bool) {
	if ( bool ) {
		$('#advanced-filter-submit').attr('disabled', 'diabled');
		navigator.geolocation.getCurrentPosition(function(position) {
			$('#advanced-filter').append('<input type="hidden" name="latitude" id="latitude" value="' + position.coords.latitude + '">')
								 .append('<input type="hidden" name="longitude" id="longitude" value="' + position.coords.longitude + '">');
			$('#advanced-filter-submit').removeAttr('disabled');
		});
	} else {
		$('#advanced-filter-submit').removeAttr('disabled');
		$('#longitude,#latitude').remove();	
	}
}
$(document).ready(function() {
	// Open targeted links in new window
	$('.open-new').attr('target','_blank');
	$('.staff-modal').click(function() {
		$.get( $(this).attr('href'), function( data ) {
			$('.modal-content').html(data);
			$('#edit-staff-modal').modal('toggle');
		});
		return false;
	});
	$('#sort-form input[type="submit"]').hide();
	$('#sort-form').change(function() {
		var sortFormData = $(this).serialize();
		var url = [location.protocol, '//', location.host, location.pathname].join('');
		window.location = url + '?' + sortFormData;
	});
	$('.alert-success').not('.keep-open').delay(1500).slideUp(750);
	disableZipAndRadius();
	$('#near-me').click(function() {
		disableZipAndRadius();
	});
	$('.copy_lrsi_value').click(function() {
		var target = $(this).attr('id').replace(/for_/, '');
		if ( $('#lrsi_' + target).size() == 1 ) {
			$('#' + target).val($('#lrsi_' + target).val());
			$('#' + target).css('border', '1px solid green');
		}
		
		return false;
	});
	$('#csl-library-view :input').prop('disabled', true);
	var orgEditHref = $('.dynamic-org-select').attr('href');
	var orgEditId = $('#organization_id').val();
	$('.dynamic-org-select').attr('href', orgEditHref + orgEditId);
	$('#organization_id').change(function() {
		orgEditId = $('#organization_id').val();
		$('.dynamic-org-select').attr('href', orgEditHref + orgEditId)	
	});
	$('#near-me').change(function() {
		toggleFilterFormCoordinates($(this).is(':checked'))
	});
});