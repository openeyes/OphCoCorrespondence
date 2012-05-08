<?php

class m120508_092806_add_print_flag_to_letter_element extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','print','tinyint(1) unsigned NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','print');
	}
}
