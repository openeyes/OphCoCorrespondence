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
			array('event_id, site_id, print, address, use_nickname, date, introduction, cc, re, body, footer, draft', 'safe'),
			array('use_nickname, site_id, date, address, introduction, body, footer', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, site_id, use_nickname, date, introduction, re, body, footer, draft', 'safe', 'on' => 'search'),
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

		if ($gp = Gp::model()->findByPk($patient->gp_id)) {
			if ($gp->contact->address) {
				$options['gp'] = $gp->contact->fullname.' (GP)';
			} else {
				$options['gp'] = $gp->contact->fullname.' (GP) - NO ADDRESS';
			}
		}

		foreach (PatientContactAssignment::model()->findAll('patient_id=?',array($patient->id)) as $pca) {
			if ($pca->contact->parent_class == 'Specialist') {
				$type = Specialist::model()->findByPk($pca->contact->parent_id)->specialist_type->name;
			} else if ($pca->contact->parent_class == 'Consultant') {
				$type = 'Consultant Ophthalmologist';
			} else {
				$type = $pca->contact->parent_class;
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

			$this->re .= ', DOB: '.$patient->NHSDate('dob').', Hosp.No: '.$patient->hos_num.', NHS no: '.$patient->nhs_num;

			$user = Yii::app()->session['user'];

			if ($contact = $user->contact) {
				$this->footer = "Yours sincerely\n\n\n\n\n".trim($contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications)."\n".$user->role;

				$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);
				$ssa = $firm->serviceSubspecialtyAssignment;

				if ($number = ElementLetter::getDirectNumber($user->id,$ssa->subspecialty_id)) {
					echo "\nDirect line: $number";
				}
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
		} else if ($this->macro->recipient_doctor && $patient->gp) {
			$this->address = $patient->gp->contact->getLetterAddress();
			$this->address_target = 'gp';
			if ($this->macro->use_nickname && $patient->gp->contact->nick_name) {
				$this->introduction = "Dear ".$patient->gp->contact->nick_name.",";
			} else {
				$this->introduction = "Dear ".$patient->gp->contact->title." ".$patient->gp->contact->last_name.",";
			}
		}

		$this->macro->substitute($patient);
		$this->body = $this->macro->body;

		if ($this->macro->cc_patient && $patient->address) {
			$this->cc = "cc:\t".$patient->title.' '.$patient->last_name.', '.implode(', ',$patient->address->getLetterarray(false));
			$this->cc_targets[] = 'patient';
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
				$cc = preg_replace('/^cc:[\s\t]+/','',trim($cc));

				$ex = explode(", ",$cc);

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
		return str_replace("\n","<br/>",trim($this->introduction));
	}

	public function renderBody() {
		$body = '';

		foreach (explode(chr(10),$this->body) as $line) {
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
		return str_replace("\n","<br/>",$this->footer);
	}

	public function renderToAddress() {
		return preg_replace('/[\r\n]+/',', ',$this->address);
	}

	static public function getDirectNumber($user_id, $subspecialty_id) {
		$number_lookup = array();
		$number_lookup[111][2] = '020 7566 2011'; // Yassir Abou-Rayyah, Adnexal
		$number_lookup[21][2] = '020 7566 2577'; // Claire Daniel, Adnexal
		$number_lookup[82][2] = '020 7566 2055'; // Myra Sloper, Adnexal
		$number_lookup[678][2] = '020 7566 2034'; // Daniel Ezra, Adnexal
		$number_lookup[73][2] = '020 7566 2034'; // Geoffrey Rose, Adnexal
		$number_lookup[18][2] = '020 7566 2577'; // Richard Collin, Adnexal
		$number_lookup[89][2] = '020 7566 2055'; // Jimmy Uddin, Adnexal
		$number_lookup[10][2] = '020 7566 2010'; // Michele Beaconsfield, Adnexal
		$number_lookup[101][2] = '020 7566 2577'; // Raja Das-Bhaumik, Adnexal
		$number_lookup[2134][2] = '020 8725 2325'; // Sarah Osborne, Adnexal
		$number_lookup[90][2] = '0207 566 2010'; // David Verity, Adnexal
		$number_lookup[41][4] = '020 8725 2325'; // Alexander Ionides, Cataract
		$number_lookup[707][4] = '020 8725 2325'; // Romesh Angunawela, Cataract
		$number_lookup[3][4] = '020 7566 2320'; // Bruce Allan, Cataract
		$number_lookup[49][4] = '020 7566 2255'; // Brian Little, Cataract
		$number_lookup[19][4] = '020 3182 4030'; // Carol Cunningham, Cataract
		$number_lookup[11][4] = '020 8967 5766'; // David Bessant, Cataract
		$number_lookup[102][4] = '020 7566 2024'; // David Gartry, Cataract
		$number_lookup[83][4] = '020 7566 2473'; // Julian D. Stevens, Cataract
		$number_lookup[29][4] = '020 7566 2018'; // Linda Ficker, Cataract
		$number_lookup[57][4] = '020 3182 4030'; // Michael Miller, Cataract
		$number_lookup[58][4] = '020 7566 2039'; // Miriam Minihan, Cataract
		$number_lookup[96][4] = '020 7566 2473'; // Mark Wilkins, Cataract
		$number_lookup[78][4] = '020 7566 2018'; // Valerie Saw, Cataract
		$number_lookup[91][4] = '020 7566 2621'; // Seema Verma, Cataract
		$number_lookup[54][4] = '020 7566 2473'; // Vincenzo Maurino, Cataract
		$number_lookup[753][4] = '020 8967 5766'; // Martin Watson, Cataract
		$number_lookup[707][6] = '020 8725 2325'; // Romesh Angunawela, External
		$number_lookup[3][6] = '020 7566 2320'; // Bruce Allan, External
		$number_lookup[102][6] = '020 7566 2024'; // David Gartry, External
		$number_lookup[47][6] = '0207 566 2045'; // Frank Larkin, External
		$number_lookup[83][6] = '020 7566 2473'; // Julian D. Stevens, External
		$number_lookup[23][6] = '020 7566 2320'; // John Dart, External
		$number_lookup[29][6] = '020 7566 2018'; // Linda Ficker, External
		$number_lookup[96][6] = '020 7566 2473'; // Mark Wilkins, External
		$number_lookup[78][6] = '020 7566 2018'; // Valerie Saw, External
		$number_lookup[88][6] = '0207 566 2045'; // Stephen Tuft, External
		$number_lookup[54][6] = '020 7566 2473'; // Vincenzo Maurino, External
		$number_lookup[753][6] = '020 8967 5766'; // Martin Watson, External
		$number_lookup[41][12] = '020 7566 2576'; // Alexander Ionides, Primary Care
		$number_lookup[82][12] = '020 7566 2621'; // Myra Sloper, Primary Care
		$number_lookup[80][12] = '020 7566 2087'; // Dilani Siriwardena, Primary Care
		$number_lookup[22][12] = '020 7566 2621'; // Rhodri Daniel, Primary Care
		$number_lookup[91][12] = '020 7566 2621'; // Seema Verma, Primary Care
		$number_lookup[92][7] = '020 8725 2325'; // Ananth Viswanathan, Glaucoma
		$number_lookup[100][7] = '020 7566 2087'; // Jonathan Clarke, Glaucoma
		$number_lookup[44][7] = '020 7566 2625'; // Deborah Kamal, Glaucoma
		$number_lookup[103][7] = '020 7566 2087'; // David Garway-Heath, Glaucoma
		$number_lookup[80][7] = '020 7566 2087'; // Dilani Siriwardena, Glaucoma
		$number_lookup[34][7] = '020 8725 2325'; // Gus Gazzard, Glaucoma
		$number_lookup[61][7] = '020 7566 2625'; // Ian Murdoch, Glaucoma
		$number_lookup[12][7] = '020 7566 2989'; // John Brookes, Glaucoma
		$number_lookup[106][7] = '020 7566 2625'; // Emma Jones, Glaucoma
		$number_lookup[9][7] = '020 7566 2625'; // Keith Barton, Glaucoma
		$number_lookup[65][7] = '020 7566 2989'; // Maria Papadopoulos, Glaucoma
		$number_lookup[324][7] = '020 7566 2087'; // Nicolas Strouthidis, Glaucoma
		$number_lookup[31][7] = '020 7566 2256'; // Paul Foster, Glaucoma
		$number_lookup[387][7] = '020 8725 2325'; // Poornima Rai, Glaucoma
		$number_lookup[45][7] = '020 7566 2989'; // Peng Khaw, Glaucoma
		$number_lookup[97][7] = '020 7566 2256'; // Richard Wormald, Glaucoma
		$number_lookup[32][7] = '020 7566 2087'; // Wendy Franks, Glaucoma
		$number_lookup[62][7] = '020 8725 2325'; // Winifred Nolan, Glaucoma
		$number_lookup[813][8] = '020 7566 2057'; // Peter Addison, Medical Retinal
		$number_lookup[93][8] = '020 7566 2278'; // Andrew Webster, Medical Retinal
		$number_lookup[87][8] = '020 7566 2576'; // Adnan Tufail, Medical Retinal
		$number_lookup[60][8] = '020 7566 2011'; // Tony Moore, Medical Retinal
		$number_lookup[64][8] = '020 7566 2419'; // Bishwanath Pal, Medical Retinal
		$number_lookup[27][8] = '020 7566 2314'; // Catherine Egan, Medical Retinal
		$number_lookup[67][8] = '020 7566 2016'; // Carlos Pavesio, Medical Retinal
		$number_lookup[30][8] = '020 7566 2039'; // Declan Flanagan, Medical Retinal
		$number_lookup[809][8] = '020 8725 2325'; // Ranjan Rajendram, Medical Retinal
		$number_lookup[26][8] = '020 8725 2325'; // Jonathan Dowler, Medical Retinal
		$number_lookup[20][8] = '020 7566 2251'; // Lyndon da Cruz, Medical Retinal
		$number_lookup[95][8] = '020 7566 2278'; // Louisa Wickham, Medical Retinal
		$number_lookup[56][8] = '020 7566 2255'; // Michel Michaelides, Medical Retinal
		$number_lookup[94][8] = '020 7566 2016'; // Mark Westcott, Medical Retinal
		$number_lookup[63][8] = '020 7566 2419'; // Narciss Okhravi, Medical Retinal
		$number_lookup[25][8] = '020 7566 2255'; // Parul Desai, Medical Retinal
		$number_lookup[40][8] = '020 8967 5766'; // Phil Hykin, Medical Retinal
		$number_lookup[109][8] = '020 7566 2255'; // Praveen Patel, Medical Retinal
		$number_lookup[6][8] = '020 7566 2255'; // Richard Andrews, Medical Retinal
		$number_lookup[37][8] = '020 8967 5766'; // Robin Hamilton, Medical Retinal
		$number_lookup[76][8] = '020 7566 2255'; // Mandeep Sagoo, Medical Retinal
		$number_lookup[38][8] = '020 8725 2325'; // Simon Horgan, Medical Retinal
		$number_lookup[108][8] = '020 7566 2252'; // Sue Lightman, Medical Retinal
		$number_lookup[117][8] = '020 8725 2325'; // Dhanes Thomas, Medical Retinal
		$number_lookup[869][8] = '020 7566 2314'; // Waheeda Rahman, Medical Retinal
		$number_lookup[35][8] = '020 7566 2039'; // Zdenek Gregor, Medical Retinal
		$number_lookup[111][11] = '020 7566 2011'; // Yassir Abou-Rayyah, Paediatrics
		$number_lookup[24][11] = '020 7566 2344'; // Alison Davis, Paediatrics
		$number_lookup[77][11] = '020 7566 2344'; // Alison Salt, Paediatrics
		$number_lookup[72][11] = '020 7566 2011'; // Ashwin Reddy, Paediatrics
		$number_lookup[60][11] = '020 7566 2011'; // Tony Moore, Paediatrics
		$number_lookup[28][11] = '020 7566 2576'; // Eric Ezra, Paediatrics
		$number_lookup[1][11] = '020 7566 2013'; // Gill Adams, Paediatrics
		$number_lookup[66][11] = '020 7566 2624'; // Himanshu Patel, Paediatrics
		$number_lookup[12][11] = '020 7566 2989'; // John Brookes, Paediatrics
		$number_lookup[112][11] = '020 7566 2346'; // James Acheson, Paediatrics
		$number_lookup[81][11] = '020 7566 2607'; // John Sloper, Paediatrics
		$number_lookup[56][11] = '020 7566 2344'; // Michel Michaelides, Paediatrics
		$number_lookup[65][11] = '020 7566 2989'; // Maria Papadopoulos, Paediatrics
		$number_lookup[2][11] = '020 7566 2624'; // Nadeem Ali, Paediatrics
		$number_lookup[45][11] = '020 7566 2989'; // Peng Khaw, Paediatrics
		$number_lookup[79][11] = '020 7566 2344'; // Andrew Sawczenko, Paediatrics
		$number_lookup[86][9] = '020 7566 2346'; // Ahmed Toosy, Neuro-ophthalmology
		$number_lookup[110][9] = '020 7566 2017'; // Gordon Plant, Neuro-ophthalmology
		$number_lookup[112][9] = '020 7566 2346'; // James Acheson, Neuro-ophthalmology
		$number_lookup[2][9] = '020 7566 2624'; // Nadeem Ali, Neuro-ophthalmology
		$number_lookup[1005][14] = '020 7566 2013'; // Annegret Dahlmann-Noor, Strabismus
		$number_lookup[24][14] = '020 7566 2024'; // Alison Davis, Strabismus
		$number_lookup[72][14] = '020 7566 2011'; // Ashwin Reddy, Strabismus
		$number_lookup[60][14] = '020 7566 2011'; // Tony Moore, Strabismus
		$number_lookup[1][14] = '020 7566 2013'; // Gill Adams, Strabismus
		$number_lookup[66][14] = '020 7566 2624'; // Himanshu Patel, Strabismus
		$number_lookup[811][14] = '020 7566 2344'; // Joanne Hancox, Strabismus
		$number_lookup[112][14] = '020 7566 2346'; // James Acheson, Strabismus
		$number_lookup[98][14] = '020 7566 2624'; // Jonathan Barnes, Strabismus
		$number_lookup[81][14] = '020 7566 2607'; // John Sloper, Strabismus
		$number_lookup[105][14] = '020 7566 2024'; // Melanie Hingorani, Strabismus
		$number_lookup[2][14] = '020 7566 2624'; // Nadeem Ali, Strabismus
		$number_lookup[85][14] = '020 8725 2325'; // Graham Thompson, Strabismus
		$number_lookup[16][16] = '020 7566 2251'; // David Charteris, Vitreoretinal
		$number_lookup[28][16] = '020 7566 2576'; // Eric Ezra, Vitreoretinal
		$number_lookup[7][16] = '020 7566 2278'; // Bill Aylward, Vitreoretinal
		$number_lookup[8][16] = '020 7566 2576'; // James Bainbridge, Vitreoretinal
		$number_lookup[20][16] = '020 7566 2251'; // Lyndon da Cruz, Vitreoretinal
		$number_lookup[95][16] = '020 8725 2325'; // Louisa Wickham, Vitreoretinal
		$number_lookup[58][16] = '020 7566 2039'; // Miriam Minihan, Vitreoretinal
		$number_lookup[84][16] = '020 7566 2039'; // Paul Sullivan, Vitreoretinal
		$number_lookup[35][16] = '020 7566 2039'; // Zdenek Gregor, Vitreoretinal

		if (isset($number_lookup[$user_id][$subspecialty_id])) {
			return $number_lookup[$user_id][$subspecialty_id];
		}

		return false;
	}
}
