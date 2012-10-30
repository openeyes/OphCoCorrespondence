<?php

class m121025_104309_findings_letter_string_group extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_string','event_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');
		$this->addColumn('et_ophcocorrespondence_letter_string','element_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string','event_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string','element_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');
		$this->addColumn('et_ophcocorrespondence_firm_letter_string','event_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');
		$this->addColumn('et_ophcocorrespondence_firm_letter_string','element_type','varchar(64) COLLATE utf8_bin DEFAULT NULL');

		foreach (Site::model()->findAll('institution_id=?',array(1)) as $site) {
			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Visual acuity',
				'body' => 'The best corrected visual acuity was [vbb]',
				'display_order' => 1,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_VisualAcuity',
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Anterior segment',
				'body' => 'Anterior segment examination showed [asr] on the right and [asl] on the left',
				'display_order' => 2,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_AnteriorSegment',
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Intraocular pressure',
				'body' => 'The intraocular pressure is [ipb]',
				'display_order' => 3,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_IntraocularPressure',
			));

			$this->insert('et_ophcocorrespondence_letter_string',array(
				'letter_string_group_id' => 2,
				'name' => 'Posterior segment',
				'body' => 'Posterior segment examination showed [psr] on the right and [psl] on the left',
				'display_order' => 4,
				'site_id' => $site->id,
				'event_type' => 'OphCiExamination',
				'element_type' => 'Element_OphCiExamination_PosteriorSegment',
			));
		}
	}

	public function down()
	{
		$this->delete('et_ophcocorrespondence_letter_string','letter_string_group_id=2');

		$this->dropColumn('et_ophcocorrespondence_letter_string','element_type');
		$this->dropColumn('et_ophcocorrespondence_letter_string','event_type');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string','element_type');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string','event_type');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string','element_type');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string','event_type');
	}
}
