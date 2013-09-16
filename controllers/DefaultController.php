<?php
/**
 * OpenEyes
*
* (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
* (C) OpenEyes Foundation, 2011-2013
* This file is part of OpenEyes.
* OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
* OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
*
* @package OpenEyes
* @link http://www.openeyes.org.uk
* @author OpenEyes <info@openeyes.org.uk>
* @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
* @copyright Copyright (c) 2011-2013, OpenEyes Foundation
* @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
*/

class DefaultController extends BaseEventTypeController
{
	public function printActions()
	{
		return array('print', 'doPrint', 'markPrinted');
	}

	//loads direct line phone numbers to trigger on drop down select
	public function loadDirectLines()
	{
		$sfs = FirmSiteSecretary::model()->findAll('firm_id=?',array(Yii::app()->session['selected_firm_id']));
		$vars[]=null;
		foreach($sfs as $sf){
			$vars[$sf->site_id]=$sf->direct_line;
		}

		$this->jsVars['correspondence_directlines']=$vars;
	}

	public function actionCreate()
	{
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception("Unknown patient: ".@$_GET['patient_id']);
		}
		$this->jsVars['OE_gp_id'] = $patient->gp_id;
		$this->jsVars['OE_practice_id'] = $patient->practice_id;

		$this->loadDirectLines();

		parent::actionCreate();
	}

	public function actionUpdate($id)
	{
		if (!$event = Event::model()->findByPk($id)) {
			throw new Exception("Unknown event: " . $id);
		}
		$this->jsVars['OE_gp_id'] = $event->episode->patient->gp_id;
		$this->jsVars['OE_practice_id'] = $event->episode->patient->practice_id;

		$this->loadDirectLines();

		parent::actionUpdate($id);
	}

	public function actionView($id)
	{
		$this->jsVars['correspondence_markprinted_url'] = Yii::app()->createUrl('OphCoCorrespondence/Default/markPrinted/'.$id);
		$this->jsVars['correspondence_print_url'] = Yii::app()->createUrl('OphCoCorrespondence/Default/print/'.$id);
		parent::actionView($id);
	}

	public function actionGetAddress()
	{
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception("Unknown patient: ".@$_GET['patient_id']);
		}

		if (!preg_match('/^([a-zA-Z]+)([0-9]+)$/',@$_GET['contact'],$m)) {
			throw new Exception("Invalid contact format: ".@$_GET['contact']);
		}

		if ($m[1] == 'Contact') {
			$contact = Person::model()->find('contact_id=?',array($m[2]));
		} else {
			if (!$contact = $m[1]::model()->findByPk($m[2])) {
				throw new Exception("{$m[1]} not found: {$m[2]}");
			}
		}

		if ($contact->isDeceased()) {
			echo json_encode(array('errors'=>'DECEASED'));
			return;
		}

		$address = $contact->getLetterAddress(array(
				'patient' => $patient,
				'include_name' => true,
				'include_label' => true,
				'delimiter' => "\n",
			));

		if(!$address){
			$address = '';
		}

		$data = array(
			'text_ElementLetter_address' => $address,
			'text_ElementLetter_introduction' => $contact->getLetterIntroduction(array(
				'nickname' => (boolean) @$_GET['nickname'],
			)),
		);

		echo json_encode($data);
	}

	public function actionGetMacroData()
	{
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
			$data['sel_address_target'] = 'Patient'.$patient->id;
			$contact = $patient;
			if ($patient->date_of_death) {
				echo json_encode(array('error'=>'DECEASED'));
				return;
			}
		}

		if ($macro->recipient_doctor && $patient->gp) {
			$data['sel_address_target'] = 'Gp'.$patient->gp_id;
			$contact = $patient->gp;
		}

		if (isset($contact)) {
			$address = $contact->getLetterAddress(array(
				'patient' => $patient,
				'include_name' => true,
				'include_label' => true,
				'delimiter' => "\n",
			));

			if($address){
				$data['text_ElementLetter_address'] = $address;
			}
			else {
				$data['alert'] = "The GP does not have a valid address.";
				$data['text_ElementLetter_address'] = '';
			}

			$data['text_ElementLetter_introduction'] = $contact->getLetterIntroduction(array(
				'nickname' => $macro->use_nickname,
			));
		}

		$data['check_ElementLetter_use_nickname'] = $macro->use_nickname;

		if ($macro->body) {
			$data['text_ElementLetter_body'] = $macro->body;
		}

		if ($macro->cc_patient) {
			if ($patient->date_of_death) {
				$data['alert'] = "Warning: the patient cannot be cc'd because they are deceased.";
			} elseif ($patient->contact->address) {
				$data['textappend_ElementLetter_cc'] = $patient->getLetterAddress(array(
					'include_name' => true,
					'include_label' => true,
					'delimiter' => ", ",
					'include_prefix' => true,
				));
				$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="Patient'.$patient->id.'" />';
			} else {
				$data['alert'] = "Letters to the GP should be cc'd to the patient, but this patient does not have a valid address.";
			}
		}

		if ($macro->cc_doctor && $patient->gp && @$patient->practice->contact->address) {
			$data['textappend_ElementLetter_cc'] = $patient->gp->getLetterAddress(array(
				'patient' => $patient,
				'include_name' => true,
				'include_label' => true,
				'delimiter' => ", ",
				'include_prefix' => true,
			));
			$data['elementappend_cc_targets'] = '<input type="hidden" name="CC_Targets[]" value="Gp'.$patient->gp_id.'" />';
		}

		echo json_encode($data);
	}

	public function actionGetString()
	{
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

	public function actionGetFrom()
	{
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

	public function actionGetCc()
	{
		if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
			throw new Exception("Unknown patient: ".@$_GET['patient_id']);
		}

		if (!preg_match('/^([a-zA-Z]+)([0-9]+)$/',@$_GET['contact'],$m)) {
			throw new Exception("Invalid contact format: ".@$_GET['contact']);
		}

		if ($m[1] == 'Contact') {
			$contact = Person::model()->find('contact_id=?',array($m[2]));
		} else {
			if (!$contact = $m[1]::model()->findByPk($m[2])) {
				throw new Exception("{$m[1]} not found: {$m[2]}");
			}
		}

		if ($contact->isDeceased()) {
			echo json_encode(array('errors'=>'DECEASED'));
			return;
		}

		$address = $contact->getLetterAddress(array(
			'patient' => $patient,
			'include_name' => true,
			'include_label' => true,
			'delimiter' => ", ",
			'include_prefix' => true,
		));

		echo $address ? $address : 'NO ADDRESS';
	}

	public function actionExpandStrings()
	{
		if (!$patient = Patient::model()->findByPk(@$_POST['patient_id'])) {
			throw new Exception('Patient not found: '.@$_POST['patient_id']);
		}

		$text = @$_POST['text'];
		$textNew = OphCoCorrespondence_Substitution::replace($text,$patient);

		if ($text != $textNew) {
			echo $textNew;
		}
	}

	public function actionMarkPrinted($id)
	{
		if ($letter = ElementLetter::model()->find('event_id=?',array($id))) {
			$letter->print = 0;
			$letter->draft = 0;
			if (!$letter->save()) {
				throw new Exception('Unable to mark letter printed: '.print_r($letter->getErrors(),true));
			}
		}
	}

	protected function printHTML($id, $elements, $template='print')
	{
		$this->printPDF($id, $elements);
	}

	protected function printPDF($id, $elements, $template='print', $params=array())
	{
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

		$from_address = $this->site->getLetterAddress(array(
			'include_name' => true,
			'delimiter' => "\n",
			'include_telephone' => true,
			'include_fax' => true,
		));

		if ($letter->direct_line) {
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

		if ($this->site->replyTo) {
			$oeletter->addReplyToAddress($this->site->getReplyToAddress(array('delimiter' => ', ', 'include_telephone' => true)));

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

				if ($this->site->replyTo) {
					$ccletter->addReplyToAddress($this->site->getReplyToAddress(array('delimiter' => ', ', 'include_telephone' => true)));
				}

				$pdf_print->addLetter($ccletter);
			}
		}

		$pdf_print->output();
	}

	public function actionUsers()
	{
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
				$consultant = $firm->consultant;
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

	public function process_examination_findings($patient_id, $element_type_id)
	{
		if ($api = Yii::app()->moduleAPI->get('OphCiExamination')) {
			if (!$patient = Patient::model()->findByPk($patient_id)) {
				throw new Exception('Unable to find patient: '.$patient_id);
			}

			if (!$element_type = ElementType::model()->findByPk($element_type_id)) {
				throw new Exception("Unknown element type: $element_type_id");
			}

			if (!$episode = $patient->getEpisodeForCurrentSubspecialty()) {
				throw new Exception('No Episode available for patient: ' . $patient_id);
			}

			return $api->getLetterStringForModel($patient, $episode, $element_type_id);
		}
	}

	public function actionDoPrint($id)
	{
		if (!$letter = ElementLetter::model()->find('event_id=?',array($id))) {
			throw new Exception("Letter not found for event id: $id");
		}

		$letter->print = 1;
		$letter->draft = 0;

		if (@$_GET['all']) {
			$letter->print_all = 1;
		}

		if (!$letter->save()) {
			throw new Exception("Unable to save letter: ".print_r($letter->getErrors(),true));
		}

		if (!$event = Event::model()->findByPk($id)) {
			throw new Exception("Event not found: $id");
		}

		$event->info = '';

		if (!$event->save()) {
			throw new Exception("Unable to save event: ".print_r($event->getErrors(),true));
		}

		echo "1";
	}
}
