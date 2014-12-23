$(document).ready(function() {
	$('#type').change(function() {
		setTypeFilter($(this).val());
		updateMacroList(0);
	});

	$('#site_id').change(function() {
		updateMacroList(0);
	});

	$('#subspecialty_id').change(function() {
		updateMacroList(0);
	});

	$('#firm_id').change(function() {
		updateMacroList(0);
	});

	$('#name').change(function() {
		updateMacroList(1);
	});

	$('#episode_status_id').change(function() {
		updateMacroList(1);
	});

	$('.addLetterMacro').click(function(e) {
		e.preventDefault();

		window.location.href = baseUrl + '/OphCoCorrespondence/admin/addMacro';
	});

	handleButton($('.cancelEditMacro'),function(e) {
		e.preventDefault();

		window.location.href = baseUrl = '/OphCoCorrespondence/admin/letterMacros';
	});

	$('#LetterMacro_type').change(function() {
		setTypeFilter($(this).val());
	});
});

function setTypeFilter(type)
{
	var site = 0;
	var subspecialty = 0;
	var firm = 0;

	switch (type) {
		case 'site':
			site = 1; break;
		case 'subspecialty':
			subspecialty = 1; break;
		case 'firm':
			firm = 1; break;
	}

	if (site) {
		$('.typeSite').show();
	} else {
		$('.typeSite').hide();
		$('#site_id').val('');
	}

	if (subspecialty) {
		$('.typeSubspecialty').show();
	} else {
		$('.typeSubspecialty').hide();
		$('#subspecialty_id').val('');
	}

	if (firm) {
		$('.typeFirm').show();
	} else {
		$('.typeFirm').hide();
		$('#firm_id').val('');
	}
}

function updateMacroList(preserve)
{
	$('#admin_letter_macros tbody').html('<tr><td colspan="10">Searching...</td></tr>');

	var name = $('#name').val();
	var episode_status_id = $('#episode_status_id').val();

	$.ajax({
		'type': 'GET',
		'url': baseUrl + '/OphCoCorrespondence/admin/filterMacros?type=' + $('#type').val() + '&site_id=' + $('#site_id').val() + '&subspecialty_id=' + $('#subspecialty_id').val() + '&firm_id=' + $('#firm_id').val() + '&name=' + name + '&episode_status_id=' + episode_status_id,
		'success': function(html) {
			$('#admin_letter_macros tbody').html(html);
		}
	});

	$.ajax({
		'type': 'GET',
		'url': baseUrl + '/OphCoCorrespondence/admin/filterMacroNames?type=' + $('#type').val() + '&site_id=' + $('#site_id').val() + '&subspecialty_id=' + $('#subspecialty_id').val() + '&firm_id=' + $('#firm_id').val(),
		'success': function(html) {
			$('#name').html(html);

			if (preserve) {
				$('#name').val(name);
			}
		}
	});

	$.ajax({
		'type': 'GET',
		'url': baseUrl + '/OphCoCorrespondence/admin/filterEpisodeStatuses?type=' + $('#type').val() + '&site_id=' + $('#site_id').val() + '&subspecialty_id=' + $('#subspecialty_id').val() + '&firm_id=' + $('#firm_id').val(),
		'success': function(html) {
			$('#episode_status_id').html(html);

			if (preserve) {
				$('#episode_status_id').val(episode_status_id);
			}
		}
	});
}
