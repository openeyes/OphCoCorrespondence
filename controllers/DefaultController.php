<?php

class DefaultController extends BaseEventTypeController {
	public function actionCreate() {
		parent::actionCreate();
	}

	public function actionUpdate($id) {
		parent::actionUpdate($id);
	}

	public function actionView($id) {
		parent::actionView($id);
	}

	public function actionGetAddress() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		$nickname = (boolean)@$_GET['nickname'];

		$data = array();

		if (@$_GET['address_id'] == 'patient') {
			$contact = $patient;
		} else if (@$_GET['address_id'] == 'gp') {
			$contact = $patient->gp->contact;
		} else if (preg_match('/^contact([0-9]+)$/',@$_GET['address_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
		} else {
			throw new Exception('Unknown or missing address_id value: '.@$_GET['address_id']);
		}

		$data['text_ElementLetter_address'] = $contact->fullName;

		if (isset($contact->qualifications) && $contact->qualifications) {
			$data['text_ElementLetter_address'] .= ' '.$contact->qualifications;
		}

		$data['text_ElementLetter_address'] .= "\n".implode("\n",$contact->address->getLetterarray(false));

		if ($nickname && isset($contact->nick_name) && $contact->nick_name) {
			$data['text_ElementLetter_introduction'] = "Dear ".$contact->nick_name.",";
		} else {
			$data['text_ElementLetter_introduction'] = "Dear ".$contact->title." ".$contact->last_name.",";
		}

		echo json_encode($data);
	}

	public function actionGetMacroData() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		switch (@$_GET['macro_type']) {
			case 'site':
				if (!$macro = SiteLetterMacro::model()->findByPk(@$_GET['macro_id'])) {
					throw new Exception('Site macro not found: '.@$_GET['macro_id']);
				}
				break;
			case 'subspecialty':
				if (!$macro = SubspecialtyLetterMacro::model()->findByPk(@$_GET['macro_id'])) {
					throw new Exception('Subspecialty macro not found: '.@$_GET['macro_id']);
				}
				break;
			case 'firm':
				if (!$macro = FirmLetterMacro::model()->findByPk(@$_GET['macro_id'])) {
					throw new Exception('Firm macro not found: '.@$_GET['macro_id']);
				}
				break;
			default:
				throw new Exception('Unknown macro type: '.@$_GET['macro_type']);
		}

		$data = array();

		$macro->substitute($patient);

		if ($macro->recipient_patient) {
			$data['sel_address_target'] = 'patient';
			$contact = $patient;
		}

		if ($macro->recipient_doctor) {
			$data['sel_address_target'] = 'gp';
			$contact = $patient->gp->contact;
		}

		if (isset($contact)) {
			$data['text_ElementLetter_address'] = $contact->fullName;
			if (isset($contact->qualifications) && $contact->qualifications) {
				$data['text_ElementLetter_address'] .= ' '.$contact->qualifications;
			}
			$data['text_ElementLetter_address'] .= "\n".implode("\n",$contact->address->getLetterarray(false));
		}

		if ($macro->use_nickname) {
			$data['check_ElementLetter_use_nickname'] = 1;

			if (isset($contact)) {
				if (isset($contact->nick_name) && $contact->nick_name) {
					$data['text_ElementLetter_introduction'] = "Dear ".$contact->nick_name.",";
				} else {
					$data['text_ElementLetter_introduction'] = "Dear ".$contact->title." ".$contact->last_name.",";
				}
			}
		} else {
			$data['check_ElementLetter_use_nickname'] = 0;

			if (isset($contact)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$contact->title." ".$contact->last_name.",";
			}
		}

		if ($macro->body) {
			$data['text_ElementLetter_body'] = $macro->body;
		}

		if ($macro->cc_patient) {
			$data['textappend_ElementLetter_cc'] = "cc:\t".$patient->title.' '.$patient->last_name.', '.implode(', ',$patient->address->getLetterarray(false));
			$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="patient" />';
		}

		echo json_encode($data);
	}

	public function actionGetString() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		switch (@$_GET['string_type']) {
			case 'site':
				if (!$string = SiteLetterString::model()->findByPk(@$_GET['string_id'])) {
					throw new Exception('Site letter string not found: '.@$_GET['string_id']);
				}
				break;
			case 'subspecialty':
				if (!$string = SubspecialtyLetterString::model()->findByPk(@$_GET['string_id'])) {
					throw new Exception('Subspecialty letter string not found: '.@$_GET['string_id']);
				}
				break;
			case 'firm':
				if (!$firm = FirmLetterString::model()->findByPk(@$_GET['string_id'])) {
					throw new Exception('Firm letter string not found: '.@$_GET['string_id']);
				}
			default:
				throw new Exception('Unknown letter string type: '.@$_GET['string_type']);
		}

		$string->substitute($patient);

		echo $string->body;
	}

	public function actionGetFrom() {
		if (!$contact = Contact::model()->findByPk(@$_GET['contact_id'])) {
			throw new Exception('Contact not found: '.@$_GET['contact_id']);
		}

		echo "Yours sincerely\n\n\n\n\n".$contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications."\nConsultant Ophthalmic Surgeon";
	}

	public function actionGetCc() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		if (@$_GET['contact_id'] == 'patient') {
			$contact = $patient;
		} else if (@$_GET['contact_id'] == 'gp') {
			$contact = $patient->gp->contact;
		} else if (preg_match('/^contact([0-9]+)$/',@$_GET['contact_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
		} else {
			throw new Exception('Unknown or missing contact_id value: '.@$_GET['contact_id']);
		}

		echo $contact->title.' '.$contact->last_name.', '.implode(', ',$contact->address->getLetterarray(false));
	}

	public function actionExpandStrings() {
		if (!$patient = Patient::model()->findByPk(@$_POST['patient_id'])) {
			throw new Exception('Patient not found: '.@$_POST['patient_id']);
		}

		$text = @$_POST['text'];

		preg_match_all('/\[([a-z]{3})\]/',$text,$m);

		$changed = false;

		foreach ($m[1] as $code) {
			if (isset($patient->{$code})) {
				$text = str_replace('['.$code.']',$patient->{$code},$text);
				$changed = true;
			}
		}

		if ($changed) {
			echo $text;
		}
	}
}
