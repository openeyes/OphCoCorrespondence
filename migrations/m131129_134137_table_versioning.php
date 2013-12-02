<?php

class m131129_134137_table_versioning extends OEMigration
{
	public function up()
	{
		foreach (Yii::app()->db->getSchema()->getTables() as $table) {
			if (preg_match('/^et_ophcocorrespondence/',$table->name) || preg_match('/^ophcocorrespondence_/',$table->name)) {
				if ($table->name != 'et_ophcocorrespondence_letter_old') {
					$this->createArchiveTable($table);
				}
			}
		}

		foreach (Yii::app()->db->createCommand()->select("*")->from("et_ophcocorrespondence_letter_old")->order("id asc")->queryAll() as $old_letter) {
			unset($old_letter['id']);

			$old_letter['rid'] = $old_letter['letter_id'];
			unset($old_letter['letter_id']);

			$this->insert('et_ophcocorrespondence_letter_archive', $old_letter);
		}

		$this->dropTable('et_ophcocorrespondence_letter_old');
	}

	public function down()
	{
	}
}
