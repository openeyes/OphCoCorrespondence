<?php

class m120613_090658_letter_string_site_id_field extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_string','site_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_letter_string','site_id','integer(10) unsigned NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter_string','site_id');
	}
}
