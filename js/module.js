
$(document).ready(function() {
	$('#address_target').change(function() {
		var nickname = $('#ElementLetter_use_nickname').is(':checked') ? '1' : '0';

		if ($(this).val() != '') {
			$.ajax({
				'type': 'GET',
				'dataType': 'json',
				'url': '/OphCoCorrespondence/Default/getAddress?patient_id='+patient_id+'&address_id='+$(this).val()+'&nickname='+nickname,
				'success': function(data) {
					correspondence_load_data(data);
				}
			});
		}
	});

	$('#macro').change(function() {
		var nickname = $('#ElementLetter_use_nickname').is(':checked') ? '1' : '0';

		if ($(this).val() != '') {
			$.ajax({
				'type': 'GET',
				'dataType': 'json',
				'url': '/OphCoCorrespondence/Default/getMacroData?patient_id='+patient_id+'&macro_id='+$(this).val()+'&nickname='+nickname,
				'success': function(data) {
					correspondence_load_data(data);
				}
			});
		}
	});

	$('#ElementLetter_use_nickname').click(function() {
		$('#address_target').change();
	});

	$('select.stringgroup').change(function() {
		var string_id = $(this).children('option:selected').val();

		if (string_id != '') {
			$.ajax({
				'type': 'GET',
				'url': '/OphCoCorrespondence/Default/getString?patient_id='+patient_id+'&string_id='+string_id,
				'success': function(text) {
					correspondence_append_body(text);
				}
			});
		}
	});

	$('#from').change(function() {
		var	contact_id = $(this).children('option:selected').val();

		if (contact_id != '') {
			$.ajax({
				'type': 'GET',
				'url': '/OphCoCorrespondence/Default/getFrom?contact_id='+contact_id,
				'success': function(text) {
					$('#ElementLetter_footer').html(text);
				}
			});
		}
	});

	$('#cc').change(function() {
		var contact_id = $(this).children('option:selected').val();

		if (contact_id != '') {
			var ok = true;

			$('#cc_targets').children('input').map(function() {
				if ($(this).val() == contact_id) {
					ok = false;
				}
			});

			if (!ok) return true;

			$.ajax({
				'type': 'GET',
				'url': '/OphCoCorrespondence/Default/getCc?patient_id='+patient_id+'&contact_id='+contact_id,
				'success': function(text) {
					if ($('#ElementLetter_cc').val().length >0) {
						var cur = $('#ElementLetter_cc').val();

						if (!$('#ElementLetter_cc').val().match(/[\n\r]$/)) {
							cur += "\n";
						}

						$('#ElementLetter_cc').val(cur+"\t"+text+"\n");
					} else {
						$('#ElementLetter_cc').val("cc:\t"+text+"\n");
					}

					$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="'+contact_id+'" />');
				}
			});
		}
	});
});

function correspondence_load_data(data) {
	for (var i in data) {
		if (m = i.match(/^text_(.*)$/)) {
			$('#'+m[1]).text(data[i]);
		} else if (m = i.match(/^sel_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^check_(.*)$/)) {
			$('#'+m[1]).attr('checked',(data[i] == 1 ? true : false));
		} else if (m = i.match(/^textappend_(.*)$/)) {
			$('#'+m[1]).text($('#'+m[1]).text()+data[i]);
		} else if (m = i.match(/^hidden_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^elementappend_(.*)$/)) {
			$('#'+m[1]).append(data[i]);
		}
	}
}

function correspondence_append_body(text) {
	if (!text.match(/\.$/)) {
		text = text + '.';
	}

	if ($('#ElementLetter_body').text() == '') {
		$('#ElementLetter_body').text(text);
	} else if ($('#ElementLetter_body').text().match(/\.$/)) {
		$('#ElementLetter_body').append(' '+text);
	} else if (!$('#ElementLetter_body').text().match(/[\.\s]+$/)) {
		$('#ElementLetter_body').append('. '+text);
	} else {
		$('#ElementLetter_body').append(text);
	}
}
