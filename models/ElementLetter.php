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
			array('event_id, address, use_nickname, date, introduction, cc, re, body, footer, draft', 'safe'),
			array('use_nickname, date, address, introduction, cc, body, footer', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, event_id, use_nickname, date, introduction, re, body, footer, draft', 'safe', 'on' => 'search'),
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

		foreach (Contact::model()->findAll('parent_class=:parent_class and parent_id=:parent_id',array(':parent_class'=>'Patient',':parent_id'=>$patient->id)) as $contact) {
			$options['contact'.$contact->id] = $contact->title.' '.$contact->first_name.' '.$contact->last_name.' (Ophthalmologist)';
		}

		if ($gp = Gp::model()->findByPk($patient->gp_id)) {
			$options['gp'] = $gp->contact->fullname.' (GP)';
		}

		return $options;
	}

	public function getStringGroups() {
		return LetterStringGroup::model()->findAll(array('order'=>'display_order'));
	}

	public function setDefaultOptions() {
		if (Yii::app()->getController()->getAction()->id == 'create') {
			if (!$patient = Patient::model()->findByPk(@$_GET['patient_id'])) {
				throw new Exception('Patient not found: '.@$_GET['patient_id']);
			} 

			$this->re = $patient->first_name.' '.$patient->last_name;

			foreach (array('address1','address2','city','postcode') as $field) {
				if ($patient->address->{$field}) {
					$this->re .= ', '.$patient->address->{$field};
				}
			}

			$this->re .= ', DofB: '.date('d/m/Y',strtotime($patient->dob)).', HosNum: '.$patient->hos_num;

			$contact = Yii::app()->session['user']->userContactAssignments->contact;

			$this->footer = "Yours sincerely\n\n\n\n\n".$contact->title.' '.$contact->first_name.' '.$contact->last_name.' '.$contact->qualifications."\nConsultant Ophthalmic Surgeon";
		}
	}
}
