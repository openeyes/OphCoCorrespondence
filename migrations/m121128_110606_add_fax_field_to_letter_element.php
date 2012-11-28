<?php

class m121128_110606_add_fax_field_to_letter_element extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','fax','varchar(64) COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','fax');
	}
}
