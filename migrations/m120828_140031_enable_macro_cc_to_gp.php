<?php

class m120828_140031_enable_macro_cc_to_gp extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter_macro','cc_doctor',"tinyint(1) unsigned NOT NULL DEFAULT '0'");
		$this->addColumn('et_ophcocorrespondence_firm_letter_macro','cc_doctor',"tinyint(1) unsigned NOT NULL DEFAULT '0'");
		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro','cc_doctor',"tinyint(1) unsigned NOT NULL DEFAULT '0'");
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_subspecialty_letter_macro','cc_doctor');
		$this->dropColumn('et_ophcocorrespondence_firm_letter_macro','cc_doctor');
		$this->dropColumn('et_ophcocorrespondence_letter_macro','cc_doctor');
	}
}
