<?php

class m120613_090046_letter_macro_should_have_a_site_id_column extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_macro','site_id','integer(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_letter_macro','site_id','integer(10) unsigned NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter_macro','site_id');
	}
}
