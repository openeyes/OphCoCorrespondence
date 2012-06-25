<?php

class m120625_121556_add_missing_site_id_foreign_key extends CDbMigration
{
	public function up()
	{
		$this->createIndex('et_ophcocorrespondence_lm_site_id_fk','et_ophcocorrespondence_letter_macro','site_id');
		$this->addForeignKey('et_ophcocorrespondence_lm_site_id_fk','et_ophcocorrespondence_letter_macro','site_id','site','id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophcocorrespondence_lm_site_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_lm_site_id_fk','et_ophcocorrespondence_letter_macro');
	}
}
