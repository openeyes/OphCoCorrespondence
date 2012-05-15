<?php

class m120515_085148_add_locked_field_to_letter_element extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','locked','tinyint(1) unsigned NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','locked');
	}
}
