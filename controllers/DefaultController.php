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

		$data = array();

		if (@$_GET['address_id'] == 'patient') {
			$contact = $patient;
			$address = $contact->getLetterAddress();
		} else if (@$_GET['address_id'] == 'gp') {
			$contact = ($patient->gp) ? $patient->gp->contact : null;
			$address = ($patient->practice && $patient->practice->address) ? $patient->practice->getLetterAddress($contact->fullName) : null;
			$salutation = ($contact) ? $contact->salutationName : Gp::UNKNOWN_SALUTATION;
			$nickname = ($contact) ? $contact->nick_name : Gp::UNKNOWN_NAME;
		} else if (preg_match('/^contact([0-9]+)$/',@$_GET['address_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=?',array($patient->id,$contact->id));

			if ($pca->site) {
				$address = $pca->site->getLetterAddress();
			} else if ($pca->institution) {
				$address = $pca->institution->getLetterAddress();
			} else {
				$address = $contact->getLetterAddress();
			}
		} else if (preg_match('/^contact([0-9]+)_site([0-9]+)$/',@$_GET['address_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=? and site_id=?',array($patient->id,$contact->id,$m[2]));
			$address = $pca->site->getLetterAddress();
		} else if (preg_match('/^contact([0-9]+)_institution([0-9]+)$/',@$_GET['address_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=? and institution_id=?',array($patient->id,$contact->id,$m[2]));
			$address = $pca->institution->getLetterAddress();
		} else {
			throw new Exception('Unknown or missing address_id value: '.@$_GET['address_id']);
		}

		$person = @$contact->fullName;

		if (isset($contact->parent_class)) {
			if ($contact->parent_class == 'Specialist') {
				$specialist = Specialist::model()->findByPk($contact->parent_id);
				$person .= "\n".$specialist->specialist_type->name."\n";
			} else if ($contact->parent_class == 'Consultant') {
				$person .= "\nConsultant Ophthalmologist\n";
			} else if ($contact->parent_class == 'Gp') {
				$person = '';
			} else {
				if ($uca = UserContactAssignment::model()->find('contact_id=?',array($contact->id))) {
					$person .= "\n".($uca->user->role ? $uca->user->role : 'Staff')."\n";
				} else {
					$person .= "\n".$contact->parent_class."\n";
				}
			}
		} else {
			$person = '';
		}

		$data['text_ElementLetter_address'] = $person.$address;

		$data['text_ElementLetter_introduction'] = 'Dear Sir/Madam,';
		if((boolean)@$_GET['nickname']) {
			if(isset($nickname)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$nickname.",";
			} else if($contact) {
				$data['text_ElementLetter_introduction'] = "Dear ".$contact->nick_name.",";
			}
		} else {
			if(isset($salutation)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$salutation.",";
			} else if($contact) {
				$data['text_ElementLetter_introduction'] = "Dear ".$contact->salutationName.",";
			}
		}

		echo json_encode($data);
	}

	public function actionGetMacroData() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		switch (@$_GET['macro_type']) {
			case 'site':
				if (!$macro = LetterMacro::model()->findByPk(@$_GET['macro_id'])) {
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
			if (@$patient->practice->address) {
				if(@$patient->gp->contact) {
					$address_name = $patient->gp->contact->fullName;
					$address_salutation = ($macro->use_nickname && $patient->gp->contact->nick_name) ? $patient->gp->contact->nick_name : $patient->gp->contact->salutationName;
				} else {
					$address_name = Gp::UNKNOWN_NAME;
					$address_salutation = Gp::UNKNOWN_SALUTATION;
				}
				$address = $patient->practice->getLetterAddress($address_name);
			}
		}

		if(isset($address)) {
			$data['text_ElementLetter_address'] = $address;
		} else if (isset($contact)) {
			$data['text_ElementLetter_address'] = $contact->getLetterAddress();
		}

		if ($macro->use_nickname) {
			$data['check_ElementLetter_use_nickname'] = 1;

			if(isset($address_salutation)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$address_salutation.",";
			} else if (isset($contact)) {
				if (isset($contact->nick_name) && $contact->nick_name) {
					$data['text_ElementLetter_introduction'] = "Dear ".$contact->nick_name.",";
				} else {
					$data['text_ElementLetter_introduction'] = "Dear ".$contact->salutationName.",";
				}
			}
		} else {
			$data['check_ElementLetter_use_nickname'] = 0;

			if(isset($address_salutation)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$address_salutation.",";
			} else if (isset($contact)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$contact->salutationName.",";
			}
		}

		if ($macro->body) {
			$data['text_ElementLetter_body'] = $macro->body;
		}

		if ($macro->cc_patient) {
			$data['textappend_ElementLetter_cc'] = 'Patient: '.$patient->addressName.', '.implode(', ',$patient->address->getLetterarray(false));
			$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="patient" />';
		}

		if ($macro->cc_doctor && @$patient->practice->address) {
			if(@$patient->gp->contact) {
				$data['textappend_ElementLetter_cc'] = 'GP: '.$patient->gp->contact->fullName.', '.implode(', ',$patient->practice->address->getLetterarray(false));
			} else {
				$data['textappend_ElementLetter_cc'] = 'GP: '.Gp::UNKNOWN_NAME.', '.implode(', ',$patient->practice->address->getLetterarray(false));
			}
			$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="gp" />';
		}

		echo json_encode($data);
	}

	public function actionGetString() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		switch (@$_GET['string_type']) {
			case 'site':
				if (!$string = LetterString::model()->findByPk(@$_GET['string_id'])) {
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
		if (!$user = User::model()->findByPk(@$_GET['user_id'])) {
			throw new Exception('User not found: '.@$_GET['user_id']);
		}

		if (!($contact = $user->contact)) {
			throw new Exception('User has no contact: '.@$_GET['user_id']);
		}

		echo "Yours sincerely\n\n\n\n\n".trim($contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications)."\n".$user->role;

		$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
		$ssa = $firm->serviceSubspecialtyAssignment;
	}

	public function actionGetCc() {
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception('Patient not found: '.@$_GET['patient_id']);
		}

		if (@$_GET['contact_id'] == 'patient') {
			$contact = $patient;
			$address_name = $patient->addressName;
			$address = $contact->address;
			$prefix = 'Patient';
		} else if (@$_GET['contact_id'] == 'gp') {
			if(!$contact = @$patient->gp->contact) {
				$address_name = Gp::UNKNOWN_NAME;
			}
			$address = @$patient->practice->address;
			$prefix = 'GP';
		} else if (preg_match('/^contact([0-9]+)$/',@$_GET['contact_id'],$m)) {
			$prefix = 'Consultant';
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=?',array($patient->id,$contact->id));

			$address = null;

			if ($pca->site) {
				if ($pca->site) {
					$address = $pca->site;
				}
			} else if ($pca->institution) {
				if ($pca->institution->address) {
					$address = $pca->institution->address;
				}
			} else {
				$address = $contact->address;
			}
			if ($uca = UserContactAssignment::model()->find('contact_id=?',array($contact->id))) {
				if ($contact->parent_class != 'Consultant') {
					$prefix = '';
				}
			}
		} else if (preg_match('/^contact([0-9]+)_site([0-9]+)$/',@$_GET['contact_id'],$m)) {
			$prefix = 'Consultant';
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=? and site_id=?',array($patient->id,$contact->id,$m[2]));
			$address = $pca->site;
			if ($uca = UserContactAssignment::model()->find('contact_id=?',array($contact->id))) {
				if ($contact->parent_class != 'Consultant') {
					$prefix = '';
				}
			}
		} else if (preg_match('/^contact([0-9]+)_institution([0-9]+)$/',@$_GET['contact_id'],$m)) {
			$prefix = 'Consultant';
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			$pca = PatientContactAssignment::model()->find('patient_id=? and contact_id=? and institution_id=?',array($patient->id,$contact->id,$m[2]));
			$address = $pca->institution;
			if ($uca = UserContactAssignment::model()->find('contact_id=?',array($contact->id))) {
				if ($contact->parent_class != 'Consultant') {
					$prefix = '';
				}
			}
		} else {
			throw new Exception('Unknown or missing contact_id value: '.@$_GET['contact_id']);
		}

		if ($address) {
			if ($prefix) {
				echo $prefix.': ';
			}
			if(isset($address_name)) {
				echo $address_name . ', ';
			} else if($contact) {
				echo $contact->fullName.', ';
			}
			echo implode(', ',$address->getLetterarray(false));
		} else {
			echo "NO ADDRESS";
		}
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

	public function actionMarkPrinted($id) {
		if ($letter = ElementLetter::model()->find('event_id=?',array($id))) {
			$letter->print = 0;
			$letter->draft = 0;
			if (!$letter->save()) {
				throw new Exception('Unable to mark letter printed: '.print_r($letter->getErrors(),true));
			}
		}
	}

	public function actionPrint($id) {
		if (!$event = Event::model()->findByPk($id)) {
			throw new Exception('Event not found: '.$id);
		}

		if (!$letter = ElementLetter::model()->find('event_id=?',array($id))) {
			throw new Exception('Letter not found were event_id = '.$id);
		}

		$event = Event::model()->findByPk($id);

		parent::actionPrint($id);
	}

	public function actionUsers() {
		$users = array();

		$criteria = new CDbCriteria;

		$criteria->addCondition(array("active = :active"));
		$criteria->addCondition(array("LOWER(concat_ws(' ',first_name,last_name)) LIKE :term"));

		$params[':active'] = 1;
		$params[':term'] = '%' . strtolower(strtr($_GET['term'], array('%' => '\%'))) . '%';

		$criteria->params = $params;
		$criteria->order = 'first_name, last_name';

		$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
		$consultant = $firm->getConsultantUser();

		foreach (User::model()->findAll($criteria) as $user) {
			if ($contact = $user->contact) {

				$consultant_name = false;

				if ($user->id != $consultant->id) {
					$consultant_name = trim($consultant->contact->title.' '.$consultant->contact->first_name.' '.$consultant->contact->last_name);
				}

				$users[] = array(
					'id' => $user->id,
					'value' => trim($contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications).' ('.$user->role.')',
					'fullname' => trim($contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications),
					'role' => $user->role,
					'consultant' => $consultant_name,
				);
			}
		}

		echo json_encode($users);
	}
}
