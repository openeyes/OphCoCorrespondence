<?php

class m120515_134047_store_previously_edited_letters extends CDbMigration
{
	public function up()
	{
		$this->createTable('et_ophcocorrespondence_letter_old', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'letter_id' => 'int(10) unsigned NOT NULL',
				'use_nickname' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'date' => 'datetime NOT NULL',
				'address' => 'varchar(1024) COLLATE utf8_bin DEFAULT NULL',
				'introduction' => 'varchar(255) COLLATE utf8_bin DEFAULT NULL',
				're' => 'varchar(1024) COLLATE utf8_bin DEFAULT NULL',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'footer' => 'varchar(2048) COLLATE utf8_bin DEFAULT NULL',
				'cc' => 'text COLLATE utf8_bin DEFAULT NULL',
				'draft' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'print' => "tinyint(1) unsigned NOT NULL DEFAULT '0'",
				'locked' => "tinyint(1) unsigned NOT NULL DEFAULT '0'",
				'site_id' => 'int(10) unsigned NOT NULL',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_letter_old_letter_id_fk` (`letter_id`)',
				'KEY `et_ophcocorrespondence_letter_old_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_letter_old_created_user_id_fk` (`created_user_id`)',
				'KEY `et_ophcocorrespondence_letter_old_site_id_fk` (`site_id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_old_letter_id_fk` FOREIGN KEY (`letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_old_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_old_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_old_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('et_ophcocorrespondence_letter_old');
	}
}
