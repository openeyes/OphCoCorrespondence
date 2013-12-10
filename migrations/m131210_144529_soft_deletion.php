<?php

class m131210_144529_soft_deletion extends CDbMigration
{
	public function up()
	{
		$this->addColumn('ophcocorrespondence_cbt_recipient','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophcocorrespondence_cbt_recipient_version','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophcocorrespondence_letter_enclosure','deleted','tinyint(1) unsigned not null');
		$this->addColumn('ophcocorrespondence_letter_enclosure_version','deleted','tinyint(1) unsigned not null');
	}

	public function down()
	{
		$this->dropColumn('ophcocorrespondence_cbt_recipient','deleted');
		$this->dropColumn('ophcocorrespondence_cbt_recipient_version','deleted');
		$this->dropColumn('ophcocorrespondence_letter_enclosure','deleted');
		$this->dropColumn('ophcocorrespondence_letter_enclosure_version','deleted');
	}
}
