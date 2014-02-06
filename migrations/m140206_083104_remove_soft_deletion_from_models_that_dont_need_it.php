<?php

class m140206_083104_remove_soft_deletion_from_models_that_dont_need_it extends CDbMigration
{
	public $tables = array(
		'et_ophcocorrespondence_firm_letter_macro',
		'et_ophcocorrespondence_firm_letter_string',
		'et_ophcocorrespondence_firm_site_secretary',
		'et_ophcocorrespondence_letter',
		'et_ophcocorrespondence_letter_macro',
		'et_ophcocorrespondence_letter_string',
		'et_ophcocorrespondence_letter_string_group',
		'et_ophcocorrespondence_subspecialty_letter_macro',
		'et_ophcocorrespondence_subspecialty_letter_string',
		'ophcocorrespondence_letter_enclosure',
	);

	public function up()
	{
		foreach ($this->tables as $table) {
			$this->dropColumn($table,'deleted');
			$this->dropColumn($table.'_version','deleted');

			$this->dropForeignKey("{$table}_aid_fk",$table."_version");
		}
	}

	public function down()
	{
		foreach ($this->tables as $table) {
			$this->addColumn($table,'deleted','tinyint(1) unsigned not null');
			$this->addColumn($table."_version",'deleted','tinyint(1) unsigned not null');

			$this->addForeignKey("{$table}_aid_fk",$table."_version","id",$table,"id");
		}
	}
}
