
$(document).ready(function() {
	$('#et_save_draft').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();
			$('#ElementLetter_draft').val(1);
			return true;
		}
		return false;
	});

	$('#et_save_print').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();
			$('#ElementLetter_draft').val(0);
			return true;
		}
		return false;
	});

	$('#et_cancel').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			$('#dialog-confirm-cancel').dialog({
				resizable: false,
				//height: 140,
				modal: true,
				buttons: {
					"Yes, cancel": function() {
						$(this).dialog('close');

						disableButtons();

						if (m = window.location.href.match(/\/update\/[0-9]+/)) {
							window.location.href = window.location.href.replace('/update/','/view/');
						} else {
							window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
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

	$('#et_deleteevent').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();
			return true;
		}
		return false;
	});

	$('#et_canceldelete').unbind('click').click(function() {
		if (!$(this).hasClass('inactive')) {
			disableButtons();

			if (m = window.location.href.match(/\/delete\/[0-9]+/)) {
				window.location.href = window.location.href.replace('/delete/','/view/');
			} else {
				window.location.href = baseUrl+'/patient/episodes/'+et_patient_id;
			}
		} 
		return false;
	});

	$('#address_target').change(function() {
		var nickname = $('input[id="ElementLetter_use_nickname"][type="checkbox"]').is(':checked') ? '1' : '0';

		if ($(this).children('option:selected').val() != '') {
			if ($(this).children('option:selected').text().match(/NO ADDRESS/)) {
				alert("Sorry, this contact has no address so you can't send a letter to them.");
				$(this).val(selected_recipient);
				return false;
			}

			var val = $(this).children('option:selected').val();

			if (re_field == null) {
				re_field = $('#ElementLetter_re').val();
			}

			if (val == 'patient') {
				$('#ElementLetter_re').val('');
				$('#ElementLetter_re').parent().parent().hide();
			} else {
				if (re_field != null) {
					$('#ElementLetter_re').val(re_field);
					$('#ElementLetter_re').parent().parent().show();
				}
			}

			$.ajax({
				'type': 'GET',
				'dataType': 'json',
				'url': baseUrl+'/OphCoCorrespondence/Default/getAddress?patient_id='+patient_id+'&address_id='+val+'&nickname='+nickname,
				'success': function(data) {
					correspondence_load_data(data);
					selected_recipient = val;

					// try to remove the selected recipient's address from the cc field
					if ($('#ElementLetter_cc').val().length >0) {
						$.ajax({
							'type': 'GET',
							'url': baseUrl+'/OphCoCorrespondence/Default/getCc?patient_id='+patient_id+'&contact_id='+val,
							'success': function(text) {
								if (!text.match(/NO ADDRESS/)) {
									if ($('#ElementLetter_cc').val().length >0) {
										var cur = $('#ElementLetter_cc').val();

										if (cur.indexOf(text) != -1) {
											var strings = cur.split("\n");
											var replace = '';

											for (var i in strings) {
												if (strings[i].length >0 && strings[i].indexOf(text) == -1) {
													if (replace.length >0) {
														replace += "\n";
													}
													replace += $.trim(strings[i]);
												}
											}

											$('#ElementLetter_cc').val(replace);
										}
									}

									var targets = '';

									$('#cc_targets').children().map(function() {
										if ($(this).val() != val) {
											targets += '<input type="hidden" name="CC_Targets[]" value="'+$(this).val()+'" />';
										}
									});
									$('#cc_targets').html(targets);
								}
							}
						});
					}

					// if the letter is to anyone but the GP we need to cc the GP
					if (val != 'gp') {
						$.ajax({
							'type': 'GET',
							'url': baseUrl+'/OphCoCorrespondence/Default/getCc?patient_id='+patient_id+'&contact_id=gp',
							'success': function(text) {
								if (!text.match(/NO ADDRESS/)) {
									if ($('#ElementLetter_cc').val().length >0) {
										var cur = $('#ElementLetter_cc').val();

										if (cur.indexOf(text) == -1) {
											if (!$('#ElementLetter_cc').val().match(/[\n\r]$/)) {
												cur += "\n";
											}

											$('#ElementLetter_cc').val(cur+text);
											$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="gp" />');
										}

									} else {
										$('#ElementLetter_cc').val(text);
										$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="gp" />');
									}
								} else {
									alert("Warning: letters should be cc'd to the patient's GP, but the current patient's GP has no valid address.");
								}
							}
						});
					} else {
						// if the letter is to the GP we need to cc the patient
						$.ajax({
							'type': 'GET',
							'url': baseUrl+'/OphCoCorrespondence/Default/getCc?patient_id='+patient_id+'&contact_id=patient',
							'success': function(text) {
								if (!text.match(/NO ADDRESS/)) {
									if ($('#ElementLetter_cc').val().length >0) {
										var cur = $('#ElementLetter_cc').val();

										if (cur.indexOf(text) == -1) {
											if (!$('#ElementLetter_cc').val().match(/[\n\r]$/)) {
												cur += "\n";
											}

											$('#ElementLetter_cc').val(cur+text);
											$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="patient" />');
										}

									} else {
										$('#ElementLetter_cc').val(text);
										$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="patient" />');
									}
								} else {
									alert("Warning: letters to the GP should be cc'd to the patient's, but the patient has no valid address.");
								}
							}
						});
					}
				}
			});
		}
	});

	$('#macro').change(function() {
		var nickname = $('input[id="ElementLetter_use_nickname"][type="checkbox"]').is(':checked') ? '1' : '0';
		var obj = $(this);

		if ($(this).val() != '') {
			var m = $(this).val().match(/^([a-z]+)([0-9]+)$/);

			$.ajax({
				'type': 'GET',
				'dataType': 'json',
				'url': baseUrl+'/OphCoCorrespondence/Default/getMacroData?patient_id='+patient_id+'&macro_type='+m[1]+'&macro_id='+m[2]+'&nickname='+nickname,
				'success': function(data) {
					$('#ElementLetter_cc').val('');
					$('#cc_targets').html('');
					correspondence_load_data(data);
					obj.val('');
				}
			});
		}
	});

	$('input[id="ElementLetter_use_nickname"][type="checkbox"]').click(function() {
		$('#address_target').change();
	});

	$('select.stringgroup').change(function() {
		var obj = $(this);
		var selected_val = $(this).children('option:selected').val();

		if (selected_val != '') {
			var m = selected_val.match(/^([a-z]+)([0-9]+)$/);

			$.ajax({
				'type': 'GET',
				'url': baseUrl+'/OphCoCorrespondence/Default/getString?patient_id='+patient_id+'&string_type='+m[1]+'&string_id='+m[2],
				'success': function(text) {
					correspondence_append_body(text);
					obj.val('');
				}
			});
		}
	});

	$('#from').change(function() {
		var	user_id = $(this).children('option:selected').val();
		var obj = $(this);

		if (user_id != '') {
			$.ajax({
				'type': 'GET',
				'url': baseUrl+'/OphCoCorrespondence/Default/getFrom?user_id='+user_id,
				'success': function(text) {
					$('#ElementLetter_footer').html(text);
					obj.val('');
				}
			});
		}
	});

	$('#cc').change(function() {
		var contact_id = $(this).children('option:selected').val();
		var obj = $(this);

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
				'url': baseUrl+'/OphCoCorrespondence/Default/getCc?patient_id='+patient_id+'&contact_id='+contact_id,
				'success': function(text) {
					if (!text.match(/NO ADDRESS/)) {
						if ($('#ElementLetter_cc').val().length >0) {
							var cur = $('#ElementLetter_cc').val();

							if (!$('#ElementLetter_cc').val().match(/[\n\r]$/)) {
								cur += "\n";
							}

							$('#ElementLetter_cc').val(cur+text);
						} else {
							$('#ElementLetter_cc').val(text);
						}

						$('#cc_targets').append('<input type="hidden" name="CC_Targets[]" value="'+contact_id+'" />');
					} else {
						alert("Sorry, this contact has no address and so cannot be cc'd.");
					}

					obj.val('');
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
				'url': baseUrl+'/OphCoCorrespondence/Default/expandStrings',
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
		$('#print_all_iframe').bind('load',function() {
			printLetter(true);
		});
	}

	$('#et_print').unbind('click').click(function() {
		printLetter();
		return false;
	});

	function printLetter(all) {
		$('#correspondence_out').removeClass('draft');

		var m = window.location.href.match(/\/view\/([0-9]+)/);

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/OphCoCorrespondence/Default/markPrinted/'+m[1],
			'success': function(html) {
				if (all) {
					printPDF(baseUrl+'/OphCoCorrespondence/Default/print/'+m[1],{"all":1});
				} else {
					printPDF(baseUrl+'/OphCoCorrespondence/Default/print/'+m[1],{});
				}
			}
		});
	}

	$('#et_print_all').unbind('click').click(function() {
		printLetter(true);
	});

	$('#et_confirm_printed').unbind('click').click(function() {
		var m = window.location.href.match(/\/view\/([0-9]+)/);

		$.ajax({
			'type': 'GET',
			'url': baseUrl+'/OphCoCorrespondence/Default/confirmPrinted/'+m[1],
			'success': function(html) {
				if (html != "1") {
					alert("Sorry, something went wrong. Please try again or contact support for assistance.");
				} else {
					location.reload(true);
				}
			}
		});
	});

	var selected_recipient = $('#address_target').val();

	$('#ElementLetter_body').tabby();
});

var et_oph_correspondence_body_cursor_position = 0;
var re_field = null;

function correspondence_load_data(data) {
	for (var i in data) {
		if (m = i.match(/^text_(.*)$/)) {
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^sel_(.*)$/)) {
			if (m[1] == 'address_target') {
				if (data[i] == 'patient') {
					$('#ElementLetter_re').val('');
					$('#ElementLetter_re').parent().parent().hide();
				} else {
					if (re_field != null) {
						$('#ElementLetter_re').val(re_field);
						$('#ElementLetter_re').parent().parent().show();
					}
				}
			}
			$('#'+m[1]).val(data[i]);
		} else if (m = i.match(/^check_(.*)$/)) {
			$('input[id="'+m[1]+'"][type="checkbox"]').attr('checked',(parseInt(data[i]) == 1 ? true : false));
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
	var insert_prefix = '';

	var current = $('#ElementLetter_body').val();

	text = ucfirst(text);

	if (!text.match(/\.$/)) {
		text += '. ';
	}

	if (current == '') {
		$('#ElementLetter_body').val(text);
	} else {
		// attempt to intelligently drop the text in based on what it follows
		var preceeding_blob = current.substring(0,cpos);

		if (preceeding_blob.match(/\.$/)) {
			insert_prefix = ' ';
		} else if (preceeding_blob.match(/[a-zA-Z]+$/)) {
			insert_prefix = '. ';
		}

		$('#ElementLetter_body').val(current.substring(0,cpos) + insert_prefix + text + current.substring(cpos,current.length));
	}

	et_oph_correspondence_body_cursor_position += insert_prefix.length;
	et_oph_correspondence_body_cursor_position += text.length;
}

function ucfirst(str) {
	str += '';
	var f = str.charAt(0).toUpperCase();
	return f + str.substr(1);
}

function uclower(str) {
	str += '';
	var f = str.charAt(0).toLowerCase();
	return f + str.substr(1);
}
