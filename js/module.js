
$(document).ready(function() {
	$('#et_save').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();
			return true;
		}
		return false;
	});

	$('#et_cancel').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			$('#dialog-confirm-cancel').dialog({
				resizable: false,
				height: 140,
				modal: true,
				buttons: {
					"Yes, cancel": function() {
						$(this).dialog('close');

						disableButtons();

						if (m = window.location.href.match(/\/update\/[0-9]+/)) {
							window.location.href = window.location.href.replace('/update/','/view/');
						} else {
							window.location.href = '/patient/episodes/'+et_patient_id;
						}
					},
					"No, go back": function() {
						$(this).dialog('close');
						return false;
					}
				}
			});
		}
		return false;
	});

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
			var m = $(this).val().match(/^([a-z]+)([0-9]+)$/);

			$.ajax({
				'type': 'GET',
				'dataType': 'json',
				'url': '/OphCoCorrespondence/Default/getMacroData?patient_id='+patient_id+'&macro_type='+m[1]+'&macro_id='+m[2]+'&nickname='+nickname,
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
		var selected_val = $(this).children('option:selected').val();

		if (selected_val != '') {
			var m = selected_val.match(/^([a-z]+)([0-9]+)$/);

			$.ajax({
				'type': 'GET',
				'url': '/OphCoCorrespondence/Default/getString?patient_id='+patient_id+'&string_type='+m[1]+'&string_id='+m[2],
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

	$('#ElementLetter_body').unbind('keyup').bind('keyup',function() {
		et_oph_correspondence_body_cursor_position = $(this).prop('selectionEnd');

		if (m = $(this).val().match(/\[([a-z]{3})\]/)) {

			var text = $(this).val();

			$.ajax({
				'type': 'POST',
				'url': '/OphCoCorrespondence/Default/expandStrings',
				'data': 'patient_id='+patient_id+'&text='+text,
				'success': function(resp) {
					if (resp) {
						$('#ElementLetter_body').val(resp);
					}
				}
			});
		}
	});

	$('#ElementLetter_body').unbind('click').click(function() {
		et_oph_correspondence_body_cursor_position = $(this).prop('selectionEnd');
	});

	if ($('#OphCoCorrespondence_printLetter').val() == 1) {
		var m = window.location.href.match(/\/view\/([0-9]+)/);
		$.ajax({
			'type': 'GET',
			'url': '/OphCoCorrespondence/Default/markPrinted/'+m[1],
			'success': function(html) {
			}
		});
		printUrl('/OphCoCorrespondence/Default/print/'+m[1]);
	}

	$('#et_print').unbind('click').click(function() {
		var m = window.location.href.match(/\/view\/([0-9]+)/);
		printUrl('/OphCoCorrespondence/Default/print/'+m[1]);
		return false;
	});
});

var et_oph_correspondence_body_cursor_position = 0;

function correspondence_load_data(data) {
	for (var i in data) {
		if (m = i.match(/^text_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^sel_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^check_(.*)$/)) {
			$('#'+m[1]).attr('checked',(data[i] == 1 ? true : false));
		} else if (m = i.match(/^textappend_(.*)$/)) {
			$('#'+m[1]).val($('#'+m[1]).val()+data[i]);
		} else if (m = i.match(/^hidden_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^elementappend_(.*)$/)) {
			$('#'+m[1]).append(data[i]);
		}
	}
}

function correspondence_append_body(text) {
	var cpos = et_oph_correspondence_body_cursor_position;

	var current = $('#ElementLetter_body').val();

	if (current == '') {
		$('#ElementLetter_body').val(text);
	} else {
		$('#ElementLetter_body').val(current.substring(0,cpos)+text+current.substring(cpos,current.length));
	}
}
