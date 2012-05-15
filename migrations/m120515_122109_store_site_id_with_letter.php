<?php

class m120515_122109_store_site_id_with_letter extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','site_id','int(10) NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_letter','site_id','int(10) NOT NULL');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','site_id');
	}
}
