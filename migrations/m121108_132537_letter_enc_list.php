<?php

class m121108_132537_letter_enc_list extends CDbMigration
{
	public function up()
	{
		$this->createTable('ophcocorrespondence_letter_enclosure',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'element_letter_id' => 'int(10) unsigned NOT NULL',
				'content' => 'varchar(128) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'int(10) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'CONSTRAINT `ophcocorrespondence_letter_enclosure_element_letter_id_fk` FOREIGN KEY (`element_letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`)',
				'CONSTRAINT `ophcocorrespondence_letter_enclosure_lmiu_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `ophcocorrespondence_letter_enclosure_cu_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
		), 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
	}

	public function down()
	{
		$this->dropTable('ophcocorrespondence_letter_enclosure');
	}
}
