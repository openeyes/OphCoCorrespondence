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
class LetterStringGroup extends BaseEventTypeElement
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
		return 'et_ophcocorrespondence_letter_string_group';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, display_order', 'safe'),
			array('name', 'required'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('name, display_order', 'safe', 'on' => 'search'),
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

	public function getStrings() {
		$strings = array();
		$string_names = array();

		if (isset($_GET['patient_id'])) {
			$patient = Patient::model()->findByPk($_GET['patient_id']);
		} else {
			$patient = Yii::app()->getController()->patient;
		}

		if ($this->name == 'Findings') {
			if ($event_type = EventType::model()->find('class_name=?',array('OphCiExamination'))) {
				if ($episode = $patient->getEpisodeForCurrentSubspecialty()) {
					if ($event = $episode->getMostRecentEventByType($event_type->id)) {
						$criteria = new CDbCriteria;
						$criteria->compare('event_type_id',$event_type->id);
						$criteria->order = 'display_order asc';

						foreach (ElementType::model()->findAll($criteria) as $element_type) {
							if ($element_type->class_name != 'Element_OphCiExamination_Management') {
								if ($element = ModuleAPI::getmodel('OphCiExamination',$element_type->class_name)->find('event_id=?',array($event->id))) {
									$strings['examination'.$element_type->id] = $element_type->name;
								}
							}
						}
					}
				}
			}

			return $strings;
		}

		$firm = Firm::model()->findByPk(Yii::app()->session['selected_firm_id']);

		$criteria = new CDbCriteria;
		$criteria->compare('letter_string_group_id', $this->id);
		$criteria->compare('firm_id', $firm->id, true);
		$criteria->order = 'display_order asc';
		
		foreach (FirmLetterString::model()->findAll($criteria) as $flm) {
			if (!in_array($flm->name, $string_names)) {
				$strings['firm'.$flm->id] = $string_names[] = $flm->name;
			}
		}

		$criteria = new CDbCriteria;
		$criteria->compare('letter_string_group_id', $this->id);
		$criteria->compare('subspecialty_id', $firm->serviceSubspecialtyAssignment->subspecialty_id, true);
		$criteria->order = 'display_order asc';

		foreach (SubspecialtyLetterString::model()->findAll($criteria) as $slm) {
			if (!in_array($slm->name, $string_names)) {
				$strings['subspecialty'.$slm->id] = $string_names[] = $slm->name;
			}
		}

		$criteria = new CDbCriteria;
		$criteria->compare('letter_string_group_id', $this->id);
		$criteria->compare('site_id', Yii::app()->session['selected_site_id'], true);
		$criteria->order = 'display_order asc';

		foreach (LetterString::model()->findAll($criteria) as $slm) {
			if (!in_array($slm->name, $string_names)) {
				$strings['site'.$slm->id] = $string_names[] = $slm->name;
			}
		}

		return $strings;
	}
}
