<?php

class m121105_095011_add_findings_options_for_history_and_adnexal_comorbidity extends CDbMigration
{
	public function up()
	{
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>10),'display_order=1');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>20),'display_order=2');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>30),'display_order=3');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>40),'display_order=4');

		foreach (Site::model()->findAll('institution_id=?',array(1)) as $site) {
			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Adnexal comorbidity',
				'body' => 'Adnexal Comorbidity was [add] on the right and [adl] on the left',
				'display_order' => 15,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_AdnexalComorbidity',
			));
			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'History',
				'body' => '[pro] gave a history of [hpc]',
				'display_order' => 5,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_History',
			));
		}
	}

	public function down()
	{
		$this->delete('et_ophcocorrespondence_letter_string',"letter_string_group_id=2 and name = 'History'");
		$this->delete('et_ophcocorrespondence_letter_string',"letter_string_group_id=2 and name = 'Adnexal comorbidity'");

		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>1),'display_order=10');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>2),'display_order=20');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>3),'display_order=30');
		$this->update('et_ophcocorrespondence_letter_string',array('display_order'=>4),'display_order=40');
	}
}
