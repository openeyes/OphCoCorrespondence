<?php

class m121128_103043_firm_secretary_fax_numbers extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_firm_site_secretary','fax','varchar(64) COLLATE utf8_bin NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_firm_site_secretary','fax');
	}
}
