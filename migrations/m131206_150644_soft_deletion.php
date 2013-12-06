<?php

class m131206_150644_soft_deletion extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_firm_letter_macro','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_firm_letter_macro_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_firm_letter_string','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_firm_letter_string_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_firm_site_secretary','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_firm_site_secretary_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_macro','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_macro_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_string','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_string_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_string_group','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_letter_string_group_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string','deleted','tinyint(1) unsigned not null');
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_firm_letter_macro','deleted');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_macro_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string','deleted');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_string_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_firm_site_secretary','deleted');
		$this->dropColumn('et_ophcocorrespondence_firm_site_secretary_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_macro','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_macro_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_string','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_string_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_string_group','deleted');
		$this->dropColumn('et_ophcocorrespondence_letter_string_group_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_macro','deleted');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','deleted');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string','deleted');
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_string_version','deleted');
	}
}
