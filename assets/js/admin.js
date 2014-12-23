$(document).ready(function() {
	$('#site_id').change(function() {
		updateMacroList();
	});

	$('#name').change(function() {
		updateMacroList();
	});

	$('#episode_status_id').change(function() {
		updateMacroList();
	});

	$('.addLetterMacro').click(function(e) {
		e.preventDefault();

		window.location.href = baseUrl + '/OphCoCorrespondence/admin/addMacro';
	});
});

function updateMacroList()
{
	$('#admin_letter_macros tbody').html('<tr><td colspan="10">Searching...</td></tr>');

	$.ajax({
		'type': 'GET',
		'url': baseUrl + '/OphCoCorrespondence/admin/filterMacros?site_id=' + $('#site_id').val() + '&name=' + $('#name').val() + '&episode_status_id=' + $('#episode_status_id').val(),
		'success': function(html) {
			$('#admin_letter_macros tbody').html(html);
		}
	});
}
