<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * The followings are the available columns in table '':
 * @property string $id
 * @property integer $event_id
 *
 * The followings are the available model relations:
 * @property Event $event
 */
class ElementLetter extends BaseEventTypeElement
{
	public $cc_targets = array();
	public $address_target = null;
	public $lock_period_hours = 24;
	public $macro = null;

	/**
	 * Returns the static model of the specified AR class.
	 * @return ElementOperation the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'et_ophcocorrespondence_letter';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('event_id, site_id, print, address, use_nickname, date, introduction, cc, re, body, footer, draft, direct_line', 'safe'),
			array('use_nickname, site_id, date, address, introduction, body, footer', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, site_id, use_nickname, date, introduction, re, body, footer, draft, direct_line', 'safe', 'on' => 'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'element_type' => array(self::HAS_ONE, 'ElementType', 'id','on' => "element_type.class_name='".get_class($this)."'"),
			'eventType' => array(self::BELONGS_TO, 'EventType', 'event_type_id'),
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'user' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'usermodified' => array(self::BELONGS_TO, 'User', 'last_modified_user_id'),
			'site' => array(self::BELONGS_TO, 'Site', 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'use_nickname' => 'Nickname',
			'date' => 'Date',
			'introduction' => 'Introduction',
			're' => 'Re',
			'body' => 'Body',
			'footer' => 'Footer',
			'draft' => 'Draft',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, true);
		$criteria->compare('event_id', $this->event_id, true);
		
		return new CActiveDataProvider(get_class($this), array(
			'criteria' => $criteria,
		));
	}

	public function getAddress_targets() {
		if (Yii::app()->getController()->getAction()->id == 'create') {
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new Exception('patient not found: '.@$_GET['patient_id']);
			}
		} else {
			$patient = $this->event->episode->patient;
		}

		$options = array('patient' => $patient->fullname.' (Patient)');

		if ($patient->gp && $patient->gp->contact) {
			$options['gp'] = $patient->gp->contact->fullname.' (GP)';
		} else {
			$options['gp'] = Gp::UNKNOWN_NAME.' (GP)';
		}
		if(!$patient->practice || !$patient->practice->address) {
			$options['gp'] .= ' - NO ADDRESS';
		}
		
		foreach (PatientContactAssignment::model()->findAll('patient_id=?',array($patient->id)) as $pca) {
			if ($pca->contact->parent_class == 'Specialist') {
				$type = Specialist::model()->findByPk($pca->contact->parent_id)->specialist_type->name;
			} else if ($pca->contact->parent_class == 'Consultant') {
				$type = 'Consultant Ophthalmologist';
			} else {
				if ($uca = UserContactAssignment::model()->find('contact_id=?',array($pca->contact_id))) {
					$type = $uca->user->role ? $uca->user->role : 'Staff';
				} else {
					$type = $pca->contact->parent_class;
				}
			}

			if ($pca->site || $pca->institution || $pca->contact->address) {
				if ($pca->site) {
					$key = 'contact'.$pca->contact_id.'_site'.$pca->site->id;
				} else if ($pca->institution) {
					$key = 'contact'.$pca->contact_id.'_institution'.$pca->institution->id;
				} else {
					$key = 'contact'.$pca->contact_id;
				}

				$options[$key] = $pca->contact->fullname.' ('.$type.', ';
				if ($pca->site) {
					$options[$key] .= $pca->site->name.')';
				} else if ($pca->institution) {
					$options[$key] .= $pca->institution->name.')';
				} else {
					$options[$key] .= str_replace(',','',$pca->contact->address->address1).')';
				}
			} else {
				$options[$key] = $pca->contact->fullname.' ('.$type.') - NO ADDRESS';
			}
		}

		asort($options);

		return $options;
	}

	public function getStringGroups() {
		return LetterStringGroup::model()->findAll(array('order'=>'display_order'));
	}

	public function setDefaultOptions() {
		if (Yii::app()->getController()->getAction()->id == 'create') {
			$this->site_id = Yii::app()->request->cookies['site_id']->value;

			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new Exception('Patient not found: '.@$_GET['patient_id']);
			} 

			$this->re = $patient->first_name.' '.$patient->last_name;

			foreach (array('address1','address2','city','postcode') as $field) {
				if ($patient->address && $patient->address->{$field}) {
					$this->re .= ', '.$patient->address->{$field};
				}
			}

			$this->re .= ', DOB: '.$patient->NHSDate('dob').', Hosp.No: '.$patient->hos_num.', NHS no: '.$patient->nhsnum;

			$user = Yii::app()->session['user'];

			if ($contact = $user->contact) {
				$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
				$consultant = $firm->getConsultantUser();

				$this->footer = "Yours sincerely\n\n\n\n\n".trim($contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications)."\n".$user->role;

				if ($consultant && $consultant->id != $user->id) {
					$this->footer .= "\nConsultant: {$consultant->contact->title} {$consultant->contact->first_name} {$consultant->contact->last_name}";
				}

				$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
				$ssa = $firm->serviceSubspecialtyAssignment;
			}

			// Look for a macro based on the episode_status
			$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
			if ($episode = $patient->getEpisodeForCurrentSubspecialty()) {
				if (!$this->macro = FirmLetterMacro::model()->find('firm_id=? and episode_status_id=?',array($firm->id, $episode->episode_status_id))) {
					$subspecialty_id = $firm->serviceSubspecialtyAssignment->subspecialty_id;

					if (!$this->macro = SubspecialtyLetterMacro::model()->find('subspecialty_id=? and episode_status_id=?',array($subspecialty_id, $episode->episode_status_id))) {
						$this->macro = LetterMacro::model()->find('episode_status_id=?',array($episode->episode_status_id));
					}
				}
			}

			if ($this->macro) {
				$this->populate_from_macro($patient);
			}
		}
	}

	public function populate_from_macro($patient) {
		if ($this->macro->use_nickname) {
			$this->use_nickname = 1;
		}

		if ($this->macro->recipient_patient) {
			$this->address = $patient->getLetterAddress();
			$this->address_target = 'patient';
			if ($this->macro->use_nickname && $patient->nick_name) {
				$this->introduction = "Dear ".$patient->nick_name.",";
			} else {
				$this->introduction = "Dear ".$patient->title." ".$patient->last_name.",";
			}
		} else if ($this->macro->recipient_doctor && @$patient->practice->address) {
			if($patient->gp && $patient->gp->contact) {
				$gp_name = $patient->gp->contact->fullName;
			} else {
				$gp_name = Gp::UNKNOWN_NAME;
			}
			$this->address = $patient->practice->getLetterAddress($gp_name);
			$this->address_target = 'gp';
			if ($this->macro->use_nickname && @$patient->gp->contact->nick_name) {
				$this->introduction = "Dear ".$patient->gp->contact->nick_name.",";
			} else if(@$patient->gp->contact->salutationName) {
				$this->introduction = "Dear ".$patient->gp->contact->salutationName.",";
			} else {
				$this->introduction = "Dear " . Gp::UNKNOWN_SALUTATION . ",";
			}
		}

		$this->macro->substitute($patient);
		$this->body = $this->macro->body;

		if ($this->macro->cc_patient && $patient->address) {
			$this->cc = 'Patient: '.$patient->title.' '.$patient->first_name.' '.$patient->last_name.', '.implode(', ',$patient->address->getLetterarray(false));
			$this->cc_targets[] = 'patient';
		}

		if ($this->macro->cc_doctor && @$patient->practice->address) {
			if(@$patient->gp->contact) {
				$this->cc = 'GP: '.$patient->gp->contact->title.' '.$patient->gp->contact->first_name.' '.$patient->gp->contact->last_name.', '.implode(', ',$patient->gp->contact->address->getLetterarray(false));
			} else {
				$this->cc = 'GP: '.Gp::UNKNOWN_NAME.', '.implode(', ',$patient->practice->address->getLetterarray(false));
			}
			$this->cc_targets[] = 'gp';
		}
	}

	public function getLetter_macros() {
		$macros = array();
		$macro_names = array();

		$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);

		$criteria = new CDbCriteria;
		$criteria->compare('firm_id', $firm->id, true);
		$criteria->order = 'display_order asc';

		foreach (FirmLetterMacro::model()->findAll($criteria) as $flm) {
			if (!in_array($flm->name, $macro_names)) {
				$macros['firm'.$flm->id] = $macro_names[] = $flm->name;
			}
		}

		$criteria = new CDbCriteria;
		$criteria->compare('subspecialty_id', $firm->serviceSubspecialtyAssignment->subspecialty_id, true);
		$criteria->order = 'display_order asc';

		foreach (SubspecialtyLetterMacro::model()->findAll($criteria) as $slm) {
			if (!in_array($slm->name, $macro_names)) {
				$macros['subspecialty'.$slm->id] = $macro_names[] = $slm->name;
			}
		}

		$criteria = new CDbCriteria;
		$criteria->compare('site_id', Yii::app()->session['selected_site_id'], true);
		$criteria->order = 'display_order asc';

		foreach (LetterMacro::model()->findAll($criteria) as $slm) {
			if (!in_array($slm->name, $macro_names)) {
				$macros['site'.$slm->id] = $macro_names[] = $slm->name;
			}
		}

		return $macros;
	}

	public function beforeSave() {
		if (in_array(Yii::app()->getController()->getAction()->id,array('create','update'))) {
			if (!$this->draft) {
				$this->print = 1;
			}
		}

		if (Yii::app()->getController()->getAction()->id == 'create') {
			if ($dl = FirmSiteSecretary::model()->find('firm_id=? and site_id=?',array(Yii::app()->session['selected_firm_id'],$this->site_id))) {
				$this->direct_line = $dl->direct_line;
			}
		}
		
		foreach (array('address','introduction','re','body','footer','cc') as $field) {
			$this->$field = trim($this->$field);
		}

		return parent::beforeSave();
	}

	public function getInfotext() {
		if ($this->draft) {
			return 'Letter is being drafted';
		}
	}

	public function getCcTargets() {
		$targets = array();

		if (trim($this->cc)) {
			foreach (explode("\n",trim($this->cc)) as $cc) {
				$ex = explode(", ",trim($cc));

				if (isset($ex[1]) && (ctype_digit($ex[1]) || is_int($ex[1]))) {
					$ex[1] .= ' '.$ex[2];
					unset($ex[2]);
				}

				$targets[] = explode(',',implode(',',$ex));
			}
		}

		return $targets;
	}

	public function isEditable() {
		return true;
	}

	public function save($runValidation=true, $attributes=null, $allow_overriding=false) {
		$old = new ElementLetterOld;

		if ($current = ElementLetter::model()->findByPk($this->id)) {
			$old->letter_id = $current->id;

			foreach (array('site_id','print','address','use_nickname','date','introduction','cc','re','body','footer','draft','created_date','created_user_id','last_modified_date','last_modified_user_id') as $key) {
				$old->{$key} = $current->{$key};
			}

			if (parent::save()) {
				if (!$old->save()) {
					throw new Exception('Unable to save old letter: '.print_r($old->getErrors(),true));
					return false;
				}
				return true;
			}

			return false;
		}

		return parent::save($runValidation, $attributes, $allow_overriding);
	}

	public function getFirm_members() {
		$members = CHtml::listData(Yii::app()->getController()->firm->members, 'id', 'fullNameAndTitle');

		$user = Yii::app()->session['user'];

		if (!isset($members[$user->id])) {
			$members[$user->id] = $user->fullNameAndTitle;
		}

		return $members;
	}

	public function renderIntroduction() {
		return str_replace("\n","<br/>",trim(CHtml::encode($this->introduction)));
	}

	public function renderBody() {
		$body = '';

		foreach (explode(chr(10),CHtml::encode($this->body)) as $line) {
			if (preg_match('/^([\s]+)/',$line,$m)) {
				for ($i=0; $i<strlen($m[1]); $i++) {
					$body .= '&nbsp;';
				}
				$body .= preg_replace('/^[\s]+/','',$line)."\n";
			} else {
				$body .= $line."\n";
			}
		}

		return str_replace("\n","<br/>",$body);
	}

	public function renderFooter() {
		return str_replace("\n","<br/>",CHtml::encode($this->footer));
	}

	public function renderToAddress() {
		return preg_replace('/[\r\n]+/',', ',CHtml::encode($this->address));
	}
}
