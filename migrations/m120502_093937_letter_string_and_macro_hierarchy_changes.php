<?php

class m120502_093937_letter_string_and_macro_hierarchy_changes extends CDbMigration
{
	public function up()
	{
		$this->renameTable('et_ophcocorrespondence_letter_macro','et_ophcocorrespondence_site_letter_macro');

		$this->dropForeignKey('et_ophcocorrespondence_letter_macro_created_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_letter_macro_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_letter_macro_created_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_letter_macro_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->createIndex('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_site_letter_macro','created_user_id');
		$this->createIndex('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro','last_modified_user_id');
		$this->addForeignKey('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_site_letter_macro','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro','last_modified_user_id','user','id');

		$this->addColumn('et_ophcocorrespondence_site_letter_macro','site_id','int(10) unsigned NOT NULL DEFAULT 1');
		$this->alterColumn('et_ophcocorrespondence_site_letter_macro','site_id','int(10) unsigned NOT NULL');
		$this->createIndex('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_site_letter_macro','site_id');
		$this->addForeignKey('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_site_letter_macro','site_id','site','id');

		$this->createTable('et_ophcocorrespondence_subspecialty_letter_macro', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'subspecialty_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'recipient_patient' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'recipient_doctor' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'use_nickname' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'cc_patient' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_slm2_subspecialty_id_fk` (`subspecialty_id`)',
				'KEY `et_ophcocorrespondence_slm2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_slm2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_slm2_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_slm2_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_slm2_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('et_ophcocorrespondence_firm_letter_macro', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'firm_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'recipient_patient' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'recipient_doctor' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'use_nickname' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'cc_patient' => 'tinyint(1) unsigned NOT NULL DEFAULT 0',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_flm_firm_id_fk` (`firm_id`)',
				'KEY `et_ophcocorrespondence_flm_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_flm_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_flm_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_flm_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_flm_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->renameTable('et_ophcocorrespondence_letter_string','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_ls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_ls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->createIndex('et_ophcocorrespondence_sls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','created_user_id');
		$this->createIndex('et_ophcocorrespondence_sls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_sls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string','letter_string_group_id');
		$this->createIndex('et_ophcocorrespondence_sls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string','subspecialty_id');
		$this->addForeignKey('et_ophcocorrespondence_sls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_sls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_sls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string','letter_string_group_id','et_ophcocorrespondence_letter_string_group','id');
		$this->addForeignKey('et_ophcocorrespondence_sls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string','subspecialty_id','subspecialty','id');

		$this->createTable('et_ophcocorrespondence_site_letter_string', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'letter_string_group_id' => 'int(10) unsigned NOT NULL',
				'site_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_sls2_letter_string_group_id_fk` (`letter_string_group_id`)',
				'KEY `et_ophcocorrespondence_sls2_site_id_fk` (`site_id`)',
				'KEY `et_ophcocorrespondence_sls2_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_sls2_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_sls2_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_sls2_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_sls2_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_sls2_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('et_ophcocorrespondence_firm_letter_string', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'letter_string_group_id' => 'int(10) unsigned NOT NULL',
				'firm_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_fls_letter_string_group_id_fk` (`letter_string_group_id`)',
				'KEY `et_ophcocorrespondence_fls_firm_id_fk` (`firm_id`)',
				'KEY `et_ophcocorrespondence_fls_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_fls_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_fls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fls_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fls_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_fls_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);
	}

	public function down()
	{
		$this->dropTable('et_ophcocorrespondence_firm_letter_string');
		$this->dropTable('et_ophcocorrespondence_site_letter_string');

		$this->dropForeignKey('et_ophcocorrespondence_sls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropForeignKey('et_ophcocorrespondence_sls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->dropIndex('et_ophcocorrespondence_sls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string');
		$this->createIndex('et_ophcocorrespondence_ls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','created_user_id');
		$this->createIndex('et_ophcocorrespondence_ls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','last_modified_user_id');
		$this->createIndex('et_ophcocorrespondence_ls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string','letter_string_group_id');
		$this->createIndex('et_ophcocorrespondence_ls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string','subspecialty_id');
		$this->addForeignKey('et_ophcocorrespondence_ls_created_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_ls_last_modified_user_id_fk','et_ophcocorrespondence_subspecialty_letter_string','last_modified_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_ls_letter_string_group_id_fk','et_ophcocorrespondence_subspecialty_letter_string','letter_string_group_id','et_ophcocorrespondence_letter_string_group','id');
		$this->addForeignKey('et_ophcocorrespondence_ls_subspecialty_id_fk','et_ophcocorrespondence_subspecialty_letter_string','subspecialty_id','subspecialty','id');
		$this->renameTable('et_ophcocorrespondence_subspecialty_letter_string','et_ophcocorrespondence_letter_string');

		$this->dropTable('et_ophcocorrespondence_firm_letter_macro');
		$this->dropTable('et_ophcocorrespondence_subspecialty_letter_macro');

		$this->dropForeignKey('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_site_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropColumn('et_ophcocorrespondence_site_letter_macro','site_id');

		$this->dropForeignKey('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropForeignKey('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_created_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->dropIndex('et_ophcocorrespondence_slm_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro');
		$this->createIndex('et_ophcocorrespondence_letter_macro_created_user_id_fk','et_ophcocorrespondence_site_letter_macro','created_user_id');
		$this->createIndex('et_ophcocorrespondence_letter_macro_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro','last_modified_user_id');
		$this->addForeignKey('et_ophcocorrespondence_letter_macro_created_user_id_fk','et_ophcocorrespondence_site_letter_macro','created_user_id','user','id');
		$this->addForeignKey('et_ophcocorrespondence_letter_macro_last_modified_user_id_fk','et_ophcocorrespondence_site_letter_macro','last_modified_user_id','user','id');

		$this->renameTable('et_ophcocorrespondence_site_letter_macro','et_ophcocorrespondence_letter_macro');
	}
}
