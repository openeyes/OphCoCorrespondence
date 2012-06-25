<?php

class m120625_122414_add_missing_site_id_foreign_key extends CDbMigration
{
	public function up()
	{
		$this->createIndex('et_ophcocorrespondence_ls2_created_site_id_fk','et_ophcocorrespondence_letter_string','site_id');
		$this->addForeignKey('et_ophcocorrespondence_ls2_created_site_id_fk','et_ophcocorrespondence_letter_string','site_id','site','id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophcocorrespondence_ls2_created_site_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls2_created_site_id_fk','et_ophcocorrespondence_letter_string');
	}
}
