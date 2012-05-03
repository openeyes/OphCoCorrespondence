<?php

class m120503_154330_link_macros_to_episode_statuses extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_macro','episode_status_id','int(10) unsigned NULL');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro','episode_status_id','int(10) unsigned NULL');
		$this->addColumn('et_ophcocorrespondence_firm_letter_macro','episode_status_id','int(10) unsigned NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_firm_letter_macro','episode_status_id');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_macro','episode_status_id');
		$this->dropColumn('et_ophcocorrespondence_letter_macro','episode_status_id');
	}
}
