<?php

class m121129_145842_clinic_date extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','clinic_date','date NULL DEFAULT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','clinic_date');
	}
}
