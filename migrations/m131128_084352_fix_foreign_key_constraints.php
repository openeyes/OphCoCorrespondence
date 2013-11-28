<?php

class m131128_084352_fix_foreign_key_constraints extends CDbMigration
{
	public $tables = array(
		'et_ophcocorrespondence_firm_letter_macro' => 'et_ophcocorrespondence_flm',
		'et_ophcocorrespondence_firm_letter_string' => 'et_ophcocorrespondence_fls',
		'et_ophcocorrespondence_letter' => 'et_ophcocorrespondence_letter',
		'et_ophcocorrespondence_letter_old' => 'et_ophcocorrespondence_letter_old',
		'et_ophcocorrespondence_letter_string_group' => 'et_ophcocorrespondence_lsg',
		'et_ophcocorrespondence_subspecialty_letter_macro' => 'et_ophcocorrespondence_slm2',
	);

	public function up()
	{
		foreach ($this->tables as $table => $prefix) {
			$this->dropForeignKey("{$prefix}_created_user_id_fk",$table);
			$this->dropForeignKey("{$prefix}_last_modified_user_id_fk",$table);

			$this->addForeignKey("{$prefix}_created_user_id_fk",$table,"created_user_id","user","id");
			$this->addForeignKey("{$prefix}_last_modified_user_id_fk",$table,"last_modified_user_id","user","id");
		}
	}

	public function down()
	{
		foreach ($this->tables as $table => $prefix) {
			$this->dropForeignKey("{$prefix}_created_user_id_fk",$table);
			$this->dropForeignKey("{$prefix}_last_modified_user_id_fk",$table);

			$this->addForeignKey("{$prefix}_created_user_id_fk",$table,"last_modified_user_id","user","id");
			$this->addForeignKey("{$prefix}_last_modified_user_id_fk",$table,"created_user_id","user","id");
		}
	}
}
