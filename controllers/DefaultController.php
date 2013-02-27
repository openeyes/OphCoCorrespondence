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
			if ($patient->date_of_death) {
				echo json_encode(array('error'=>'DECEASED'));
				return;
			}
		} else if (@$_GET['address_id'] == 'gp') {
			if($contact = @$patient->gp->contact) {
				$address_name = $contact->fullName;
			} else {
				$address_name = Gp::UNKNOWN_NAME;
			}
			$address = ($patient->practice && $patient->practice->address) ? $patient->practice->getLetterAddress($address_name) : null;
			$salutation = ($contact) ? $contact->salutationName : Gp::UNKNOWN_SALUTATION;
			$nickname = ($contact) ? $contact->nick_name : Gp::UNKNOWN_NAME;
		} else if (preg_match('/^contact([0-9]+)_?(site|institution)?([0-9]+)?$/',@$_GET['address_id'],$m)) {
			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}
			if ($address = $patient->getContactAddress($contact->id, @$m[2], @$m[3])) {
				$address = $address->getLetterAddress(true);
			}
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

		if ((boolean)@$_GET['nickname'] && (isset($nickname) || ($contact && $contact->nick_name))) {
			if(isset($nickname)) {
				$data['text_ElementLetter_introduction'] = "Dear ".$nickname.",";
			} else if($contact) {
				$data['text_ElementLetter_introduction'] = "Dear ".$contact->nick_name.",";
			}
		} else {
			if (isset($salutation)) {
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
			if ($patient->date_of_death) {
				echo json_encode(array('error'=>'DECEASED'));
				return;
			}
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
			if ($patient->date_of_death) {
				$data['alert'] = "Warning: the patient cannot be cc'd because they are deceased.";
			} else if ($patient->address) {
				$data['textappend_ElementLetter_cc'] = 'Patient: '.$patient->addressName.', '.implode(', ',$patient->address->getLetterarray());
				$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="patient" />';
			} else {
				$data['alert'] = "Letters to the GP should be cc'd to the patient, but this patient does not have a valid address.";
			}
		}

		if ($macro->cc_doctor && @$patient->practice->address) {
			if(@$patient->gp->contact) {
				$data['textappend_ElementLetter_cc'] = 'GP: '.$patient->gp->contact->fullName.', '.implode(', ',$patient->practice->address->getLetterarray());
			} else {
				$data['textappend_ElementLetter_cc'] = 'GP: '.Gp::UNKNOWN_NAME.', '.implode(', ',$patient->practice->address->getLetterarray());
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
				break;
			case 'examination':
				echo $this->process_examination_findings($_GET['patient_id'],$_GET['string_id']);
				return;
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

			if ($patient->date_of_death) {
				echo "DECEASED";
				return;
			}

		} else if (@$_GET['contact_id'] == 'gp') {

			if(!$contact = @$patient->gp->contact) {
				$address_name = Gp::UNKNOWN_NAME;
			}
			$address = @$patient->practice->address;
			$prefix = 'GP';

		} else if (preg_match('/^contact([0-9]+)_?(site|institution)?([0-9]+)?$/',@$_GET['contact_id'],$m)) {

			if (!$contact = Contact::model()->findByPk($m[1])) {
				throw new Exception('Unknown contact id: '.$m[1]);
			}

			$address = $patient->getContactAddress($contact->id, @$m[2], @$m[3]);
		} else {
			throw new Exception('Invalid or missing contact_id value: '.@$_GET['contact_id']);
		}

		if ($address) {
			if (isset($prefix)) {
				echo $prefix.': ';
			} else if ($contact->prefix) {
				echo $contact->prefix.': ';
			}
			if (isset($address_name)) {
				echo $address_name . ', ';
			} else if($contact) {
				echo $contact->fullName.', ';
			}
			echo implode(', ',$address->getLetterarray(true,true,true));
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

	protected function printHTML($id, $elements, $template='print') {
		$this->printPDF($id, $elements);
	}

	protected function printPDF($id, $elements, $template='print') {
		// Remove any existing css
		Yii::app()->getClientScript()->reset();
		
		if (!$letter = ElementLetter::model()->find('event_id=?',array($id))) {
			throw new Exception('Letter not found were event_id = '.$id);
		}
		$this->site = $letter->site;
		
		$this->layout = '//layouts/pdf';
		$pdf_print = new OEPDFPrint('Openeyes', 'Correspondence letters', 'Correspondence letters');

		$body = $this->render('print', array(
				'elements' => $elements,
				'eventId' => $id,
		), true);

		$from_address = $this->site->getCorrespondenceSiteName()."\n";
		$from_address .= implode("\n",$this->site->getLetterArray(false,false))."\n";
		$from_address .= "Tel: ".CHtml::encode($this->site->telephone);
		if($this->site->fax) {
			$from_address .= "\nFax: ".CHtml::encode($this->site->fax);
		}
		if($letter->direct_line) {
			$from_address .= "\nDirect line: ".$letter->direct_line;
		}
		if ($letter->fax) {
			$from_address .= "\nFax: ".$letter->fax;
		}

		$from_address .= "\n\n".$letter->NHSDate('date');

		if ($letter->clinic_date) {
			$from_address .= " (clinic date ".$letter->NHSDate('clinic_date').")";
		}

		$oeletter = new OELetter($letter->address,$from_address);
		$oeletter->setHideDate(true);
		$oeletter->setBarcode('E:'.$id);
		$oeletter->addBody($body);

		if ($this->site->replyto) {
			$oeletter->addReplyToAddress($this->site->getReplyToAddress().($this->site->replyto->primary_phone ? ', Tel: '.$this->site->replyto->primary_phone : ''));
		}

		$pdf_print->addLetter($oeletter);

		if (@$_GET['all']) {
			
			// Add copy for file
			$pdf_print->addLetter($oeletter);

			// Add CCs
			foreach ($letter->getCcTargets() as $cc) {
				$ccletter = new OELetter(implode("\n",preg_replace('/^[a-zA-Z]+: /','',$cc)),$from_address);
				$ccletter->setHideDate(true);
				$ccletter->setBarcode('E:'.$id);
				$ccletter->addBody($body);

				if ($this->site->replyto) {
					$ccletter->addReplyToAddress($this->site->getReplyToAddress().($this->site->replyto->primary_phone ? ', Tel: '.$this->site->replyto->primary_phone : ''));
				}

				$pdf_print->addLetter($ccletter);
			}
		}

		$pdf_print->output();
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
		$consultant = null;
		// only want a consultant for medical firms
		if ($specialty = $firm->getSpecialty()) {
			if ($specialty->medical) {
				$consultant = $firm->getConsultantUser();
			}
		}

		foreach (User::model()->findAll($criteria) as $user) {
			if ($contact = $user->contact) {

				$consultant_name = false;
				
				// if we have a consultant for the firm, and its not the matched user, attach the consultant name to the entry
				if ($consultant && $user->id != $consultant->id) {
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

	public function process_examination_findings($patient_id, $element_type_id) {
		if ($api = Yii::app()->moduleAPI->get('OphCiExamination')) {
			if (!$patient = Patient::model()->findByPk($patient_id)) {
				throw new Exception('Unable to find patient: '.$patient_id);
			}

			if ($element_type = ElementType::model()->findByPk($element_type_id)) {
				throw new Exception("Unknown element type: $element_type_id");
			}

			return $api->getLetterStringForModel($patient, $element_type_id);
		}
	}
}
