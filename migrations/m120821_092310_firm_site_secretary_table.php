<?php

class m120821_092310_firm_site_secretary_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('et_ophcocorrespondence_firm_site_secretary',array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'firm_id' => 'int(10) unsigned NOT NULL',
				'site_id' => 'int(10) unsigned NULL',
				'direct_line' => 'varchar(64) COLLATE utf8_bin NOT NULL',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_fss_firm_id_fk` (`firm_id`)',
				'KEY `et_ophcocorrespondence_fss_site_id_fk` (`site_id`)',
				'KEY `et_ophcocorrespondence_fss_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_fss_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_fss_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fss_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fss_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fss_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('et_ophcocorrespondence_firm_site_secretary');
	}
}
