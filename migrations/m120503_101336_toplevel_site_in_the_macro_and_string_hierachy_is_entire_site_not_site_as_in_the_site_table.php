<?php

class m120503_101336_toplevel_site_in_the_macro_and_string_hierachy_is_entire_site_not_site_as_in_the_site_table extends CDbMigration
{
	public function up()
	{
		$this->renameTable('et_ophcocorrespondence_site_letter_macro','et_ophcocorrespondence_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->createIndex('et_ophcocorrespondence_lm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_lm_created_user_id_fk','et_ophcocorrespondence_letter_macro','created_user_id');
		$this->addForeignKey('et_ophcocorrespondence_lm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_lm_created_user_id_fk','et_ophcocorrespondence_letter_macro','created_user_id','user','id');
		$this->dropColumn('et_ophcocorrespondence_letter_macro','site_id');

		$this->renameTable('et_ophcocorrespondence_site_letter_string','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls2_site_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls2_created_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls2_site_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls2_created_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->createIndex('et_ophcocorrespondence_ls2_created_user_id_fk','et_ophcocorrespondence_letter_string','created_user_id');
		$this->createIndex('et_ophcocorrespondence_ls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_ls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string','letter_string_group_id');
		$this->addForeignKey('et_ophcocorrespondence_ls2_created_user_id_fk','et_ophcocorrespondence_letter_string','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_ls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_ls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string','letter_string_group_id','et_ophcocorrespondence_letter_string_group','id');
		$this->dropColumn('et_ophcocorrespondence_letter_string','site_id');
	}

	public function down()
	{
		$this->dropForeignKey('et_ophcocorrespondence_lm_created_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_lm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_lm_created_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_lm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro');
		$this->addColumn('et_ophcocorrespondence_letter_macro','site_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_letter_macro','site_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_letter_macro','created_user_id');
		$this->createIndex('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_letter_macro','site_id');
		$this->addForeignKey('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_letter_macro','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_letter_macro','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_letter_macro','site_id','site','id');
		$this->renameTable('et_ophcocorrespondence_letter_macro','et_ophcocorrespondence_site_letter_macro');

		$this->dropForeignKey('et_ophcocorrespondence_ls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls2_created_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls2_created_user_id_fk','et_ophcocorrespondence_letter_string');
		$this->addColumn('et_ophcocorrespondence_letter_string','site_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_letter_string','site_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophcocorrespondence_sls2_created_user_id_fk','et_ophcocorrespondence_letter_string','created_user_id');
		$this->createIndex('et_ophcocorrespondence_sls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_sls2_site_id_fk','et_ophcocorrespondence_letter_string','site_id');
		$this->createIndex('et_ophcocorrespondence_sls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string','letter_string_group_id');
		$this->addForeignKey('et_ophcocorrespondence_sls2_created_user_id_fk','et_ophcocorrespondence_letter_string','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_sls2_last_modified_user_id_fk','et_ophcocorrespondence_letter_string','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_sls2_site_id_fk','et_ophcocorrespondence_letter_string','site_id','site','id');
		$this->addForeignKey('et_ophcocorrespondence_sls2_letter_string_group_id_fk','et_ophcocorrespondence_letter_string','letter_string_group_id','et_ophcocorrespondence_letter_string_group','id');
		$this->renameTable('et_ophcocorrespondence_letter_string','et_ophcocorrespondence_site_letter_string');
	}
}
