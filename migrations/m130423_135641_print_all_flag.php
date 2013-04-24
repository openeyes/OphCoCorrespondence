<?php

class m130423_135641_print_all_flag extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','print_all','tinyint(1) unsigned NOT NULL DEFAULT 0');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','print_all');
	}
}
