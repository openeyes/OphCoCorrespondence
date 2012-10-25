<?php

class m121024_093540_findings_letter_string_group extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_string','event_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_letter_string_event_type_id_fk','et_ophcocorrespondence_letter_string','event_type_id');
		$this->addForeignKey('et_ophcocorrespondence_letter_string_event_type_id_fk','et_ophcocorrespondence_letter_string','event_type_id','event_type','id');

		$this->addColumn('et_ophcocorrespondence_letter_string','element_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_letter_string_element_type_id_fk','et_ophcocorrespondence_letter_string','element_type_id');
		$this->addForeignKey('et_ophcocorrespondence_letter_string_element_type_id_fk','et_ophcocorrespondence_letter_string','element_type_id','element_type','id');

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string','event_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_subspecialty_letter_string_eti_fk','et_ophcocorrespondence_subspecialty_letter_string','event_type_id');
		$this->addForeignKey('et_ophcocorrespondence_subspecialty_letter_string_eti_fk','et_ophcocorrespondence_subspecialty_letter_string','event_type_id','event_type','id');

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string','element_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_subspecialty_letter_string_elti_fk','et_ophcocorrespondence_subspecialty_letter_string','element_type_id');
		$this->addForeignKey('et_ophcocorrespondence_subspecialty_letter_string_elti_fk','et_ophcocorrespondence_subspecialty_letter_string','element_type_id','element_type','id');

		$this->addColumn('et_ophcocorrespondence_firm_letter_string','event_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_firm_letter_string_eti_fk','et_ophcocorrespondence_firm_letter_string','event_type_id');
		$this->addForeignKey('et_ophcocorrespondence_firm_letter_string_eti_fk','et_ophcocorrespondence_firm_letter_string','event_type_id','event_type','id');

		$this->addColumn('et_ophcocorrespondence_firm_letter_string','element_type_id','int(10) unsigned NULL');
		$this->createIndex('et_ophcocorrespondence_firm_letter_string_elti_fk','et_ophcocorrespondence_firm_letter_string','element_type_id');
		$this->addForeignKey('et_ophcocorrespondence_firm_letter_string_elti_fk','et_ophcocorrespondence_firm_letter_string','element_type_id','element_type','id');

		$examination = EventType::model()->find('class_name=?',array('OphCiExamination'));
		$visual_acuity = ElementType::model()->find('event_type_id=? and class_name=?',array($examination->id,'Element_OphCiExamination_VisualAcuity'));
		$anterior_segment = ElementType::model()->find('event_type_id=? and class_name=?',array($examination->id,'Element_OphCiExamination_AnteriorSegment'));
		$intraocular_pressure = ElementType::model()->find('event_type_id=? and class_name=?',array($examination->id,'Element_OphCiExamination_IntraocularPressure'));
		$posterior_segment = ElementType::model()->find('event_type_id=? and class_name=?',array($examination->id,'Element_OphCiExamination_PosteriorSegment'));

		foreach (Site::model()->findAll('institution_id=?',array(1)) as $site) {
			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Visual acuity',
				'body' => 'The best corrected visual acuity was [vbb]',
				'display_order' => 1,
				'site_id' => $site->id,
				'event_type_id' => $examination->id,
				'element_type_id' => $visual_acuity->id,
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Anterior segment',
				'body' => 'Anterior segment examination showed [asr] on the right and [asl] on the left',
				'display_order' => 2,
				'site_id' => $site->id,
				'event_type_id' => $examination->id,
				'element_type_id' => $anterior_segment->id,
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Intraocular pressure',
				'body' => 'The intraocular pressure is [ipb]',
				'display_order' => 3,
				'site_id' => $site->id,
				'event_type_id' => $examination->id,
				'element_type_id' => $intraocular_pressure->id,
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Posterior segment',
				'body' => 'Posterior segment examination showed [psr] on the right and [psl] on the left',
				'display_order' => 4,
				'site_id' => $site->id,
				'event_type_id' => $examination->id,
				'element_type_id' => $posterior_segment->id,
			));
		}
	}

	public function down()
	{
		$this->delete('et_ophcocorrespondence_letter_string','letter_string_group_id=2');

		$this->dropForeignKey('et_ophcocorrespondence_letter_string_element_type_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_letter_string_element_type_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropColumn('et_ophcocorrespondence_letter_string','element_type_id');

		$this->dropForeignKey('et_ophcocorrespondence_letter_string_event_type_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_letter_string_event_type_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropColumn('et_ophcocorrespondence_letter_string','event_type_id');

		$this->dropForeignKey('et_ophcocorrespondence_subspecialty_letter_string_elti_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_subspecialty_letter_string_elti_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string','element_type_id');

		$this->dropForeignKey('et_ophcocorrespondence_subspecialty_letter_string_eti_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_subspecialty_letter_string_eti_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string','event_type_id');
	
		$this->dropForeignKey('et_ophcocorrespondence_firm_letter_string_elti_fk','et_ophcocorrespondence_firm_letter_string');
		$this->dropIndex('et_ophcocorrespondence_firm_letter_string_elti_fk','et_ophcocorrespondence_firm_letter_string');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string','element_type_id');

		$this->dropForeignKey('et_ophcocorrespondence_firm_letter_string_eti_fk','et_ophcocorrespondence_firm_letter_string');
		$this->dropIndex('et_ophcocorrespondence_firm_letter_string_eti_fk','et_ophcocorrespondence_firm_letter_string');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string','event_type_id');
	}
}
