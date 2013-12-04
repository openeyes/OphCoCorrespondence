<?php

class m131129_134137_table_versioning extends OEMigration
{
	public function up()
	{
		$this->execute("
CREATE TABLE `et_ophcocorrespondence_firm_letter_macro_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`firm_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`recipient_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`recipient_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`use_nickname` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`body` text COLLATE utf8_bin,
	`cc_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`episode_status_id` int(10) unsigned DEFAULT NULL,
	`cc_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`cc_drss` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_flm_firm_id_fk` (`firm_id`),
	KEY `acv_et_ophcocorrespondence_flm_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_flm_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_flm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_flm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_flm_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_firm_letter_macro_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_firm_letter_macro_version');

		$this->createIndex('et_ophcocorrespondence_firm_letter_macro_aid_fk','et_ophcocorrespondence_firm_letter_macro_version','id');
		$this->addForeignKey('et_ophcocorrespondence_firm_letter_macro_aid_fk','et_ophcocorrespondence_firm_letter_macro_version','id','et_ophcocorrespondence_firm_letter_macro','id');

		$this->addColumn('et_ophcocorrespondence_firm_letter_macro_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_firm_letter_macro_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_firm_letter_macro_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_firm_letter_macro_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_firm_letter_string_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`letter_string_group_id` int(10) unsigned NOT NULL,
	`firm_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`body` text COLLATE utf8_bin,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`event_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`element_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_fls_letter_string_group_id_fk` (`letter_string_group_id`),
	KEY `acv_et_ophcocorrespondence_fls_firm_id_fk` (`firm_id`),
	KEY `acv_et_ophcocorrespondence_fls_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_fls_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fls_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fls_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fls_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_firm_letter_string_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_firm_letter_string_version');

		$this->createIndex('et_ophcocorrespondence_firm_letter_string_aid_fk','et_ophcocorrespondence_firm_letter_string_version','id');
		$this->addForeignKey('et_ophcocorrespondence_firm_letter_string_aid_fk','et_ophcocorrespondence_firm_letter_string_version','id','et_ophcocorrespondence_firm_letter_string','id');

		$this->addColumn('et_ophcocorrespondence_firm_letter_string_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_firm_letter_string_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_firm_letter_string_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_firm_letter_string_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_firm_site_secretary_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`firm_id` int(10) unsigned NOT NULL,
	`site_id` int(10) unsigned DEFAULT NULL,
	`direct_line` varchar(64) COLLATE utf8_bin NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`fax` varchar(64) COLLATE utf8_bin NOT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_fss_firm_id_fk` (`firm_id`),
	KEY `acv_et_ophcocorrespondence_fss_site_id_fk` (`site_id`),
	KEY `acv_et_ophcocorrespondence_fss_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_fss_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fss_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fss_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fss_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_fss_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_firm_site_secretary_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_firm_site_secretary_version');

		$this->createIndex('et_ophcocorrespondence_firm_site_secretary_aid_fk','et_ophcocorrespondence_firm_site_secretary_version','id');
		$this->addForeignKey('et_ophcocorrespondence_firm_site_secretary_aid_fk','et_ophcocorrespondence_firm_site_secretary_version','id','et_ophcocorrespondence_firm_site_secretary','id');

		$this->addColumn('et_ophcocorrespondence_firm_site_secretary_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_firm_site_secretary_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_firm_site_secretary_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_firm_site_secretary_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_letter_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`event_id` int(10) unsigned NOT NULL,
	`use_nickname` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`date` datetime NOT NULL,
	`address` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
	`introduction` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	`re` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
	`body` text COLLATE utf8_bin,
	`footer` varchar(2048) COLLATE utf8_bin DEFAULT NULL,
	`cc` text COLLATE utf8_bin,
	`draft` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`print` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`site_id` int(10) NOT NULL,
	`direct_line` varchar(32) COLLATE utf8_bin DEFAULT NULL,
	`fax` varchar(64) COLLATE utf8_bin NOT NULL,
	`clinic_date` date DEFAULT NULL,
	`print_all` tinyint(1) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_letter_event_id_fk` (`event_id`),
	KEY `acv_et_ophcocorrespondence_letter_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_letter_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_letter_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_letter_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_letter_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_letter_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_letter_version');

		$this->createIndex('et_ophcocorrespondence_letter_aid_fk','et_ophcocorrespondence_letter_version','id');
		$this->addForeignKey('et_ophcocorrespondence_letter_aid_fk','et_ophcocorrespondence_letter_version','id','et_ophcocorrespondence_letter','id');

		$this->addColumn('et_ophcocorrespondence_letter_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_letter_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_letter_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_letter_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_letter_macro_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`recipient_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`recipient_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`use_nickname` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`body` text COLLATE utf8_bin,
	`cc_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`episode_status_id` int(10) unsigned DEFAULT NULL,
	`site_id` int(10) unsigned NOT NULL,
	`cc_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`cc_drss` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_lm_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_lm_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophcocorrespondence_lm_site_id_fk` (`site_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_lm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_lm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_lm_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_letter_macro_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_letter_macro_version');

		$this->createIndex('et_ophcocorrespondence_letter_macro_aid_fk','et_ophcocorrespondence_letter_macro_version','id');
		$this->addForeignKey('et_ophcocorrespondence_letter_macro_aid_fk','et_ophcocorrespondence_letter_macro_version','id','et_ophcocorrespondence_letter_macro','id');

		$this->addColumn('et_ophcocorrespondence_letter_macro_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_letter_macro_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_letter_macro_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_letter_macro_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_letter_string_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`letter_string_group_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`body` text COLLATE utf8_bin,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`site_id` int(10) unsigned NOT NULL,
	`event_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`element_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_ls2_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophcocorrespondence_ls2_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_ls2_letter_string_group_id_fk` (`letter_string_group_id`),
	KEY `acv_et_ophcocorrespondence_ls2_created_site_id_fk` (`site_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_ls2_created_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_ls2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_ls2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_ls2_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_letter_string_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_letter_string_version');

		$this->createIndex('et_ophcocorrespondence_letter_string_aid_fk','et_ophcocorrespondence_letter_string_version','id');
		$this->addForeignKey('et_ophcocorrespondence_letter_string_aid_fk','et_ophcocorrespondence_letter_string_version','id','et_ophcocorrespondence_letter_string','id');

		$this->addColumn('et_ophcocorrespondence_letter_string_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_letter_string_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_letter_string_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_letter_string_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_letter_string_group_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_lsg_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_lsg_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_lsg_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_lsg_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_letter_string_group_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_letter_string_group_version');

		$this->createIndex('et_ophcocorrespondence_letter_string_group_aid_fk','et_ophcocorrespondence_letter_string_group_version','id');
		$this->addForeignKey('et_ophcocorrespondence_letter_string_group_aid_fk','et_ophcocorrespondence_letter_string_group_version','id','et_ophcocorrespondence_letter_string_group','id');

		$this->addColumn('et_ophcocorrespondence_letter_string_group_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_letter_string_group_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_letter_string_group_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_letter_string_group_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_subspecialty_letter_macro_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`subspecialty_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`recipient_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`recipient_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`use_nickname` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`body` text COLLATE utf8_bin,
	`cc_patient` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`episode_status_id` int(10) unsigned DEFAULT NULL,
	`cc_doctor` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`cc_drss` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_slm2_subspecialty_id_fk` (`subspecialty_id`),
	KEY `acv_et_ophcocorrespondence_slm2_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_slm2_created_user_id_fk` (`created_user_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_slm2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_slm2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_slm2_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_subspecialty_letter_macro_version');

		$this->createIndex('et_ophcocorrespondence_subspecialty_letter_macro_aid_fk','et_ophcocorrespondence_subspecialty_letter_macro_version','id');
		$this->addForeignKey('et_ophcocorrespondence_subspecialty_letter_macro_aid_fk','et_ophcocorrespondence_subspecialty_letter_macro_version','id','et_ophcocorrespondence_subspecialty_letter_macro','id');

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_subspecialty_letter_macro_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_subspecialty_letter_macro_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `et_ophcocorrespondence_subspecialty_letter_string_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`letter_string_group_id` int(10) unsigned NOT NULL,
	`subspecialty_id` int(10) unsigned NOT NULL,
	`name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`body` text COLLATE utf8_bin,
	`display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`event_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	`element_type` varchar(64) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `acv_et_ophcocorrespondence_sls_created_user_id_fk` (`created_user_id`),
	KEY `acv_et_ophcocorrespondence_sls_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `acv_et_ophcocorrespondence_sls_letter_string_group_id_fk` (`letter_string_group_id`),
	KEY `acv_et_ophcocorrespondence_sls_subspecialty_id_fk` (`subspecialty_id`),
	CONSTRAINT `acv_et_ophcocorrespondence_sls_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_sls_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_sls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`),
	CONSTRAINT `acv_et_ophcocorrespondence_sls_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('et_ophcocorrespondence_subspecialty_letter_string_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','et_ophcocorrespondence_subspecialty_letter_string_version');

		$this->createIndex('et_ophcocorrespondence_subspecialty_letter_string_aid_fk','et_ophcocorrespondence_subspecialty_letter_string_version','id');
		$this->addForeignKey('et_ophcocorrespondence_subspecialty_letter_string_aid_fk','et_ophcocorrespondence_subspecialty_letter_string_version','id','et_ophcocorrespondence_subspecialty_letter_string','id');

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('et_ophcocorrespondence_subspecialty_letter_string_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','et_ophcocorrespondence_subspecialty_letter_string_version','version_id');
		$this->alterColumn('et_ophcocorrespondence_subspecialty_letter_string_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophcocorrespondence_cbt_recipient_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`commissioning_body_type_id` int(10) unsigned NOT NULL,
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1901-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophcocorrespondence_cbt_recipient_lmui_fk` (`last_modified_user_id`),
	KEY `acv_ophcocorrespondence_cbt_recipient_cui_fk` (`created_user_id`),
	KEY `acv_ophcocorrespondence_cbt_recipient_cbti_fk` (`commissioning_body_type_id`),
	CONSTRAINT `acv_ophcocorrespondence_cbt_recipient_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophcocorrespondence_cbt_recipient_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophcocorrespondence_cbt_recipient_cbti_fk` FOREIGN KEY (`commissioning_body_type_id`) REFERENCES `commissioning_body_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophcocorrespondence_cbt_recipient_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophcocorrespondence_cbt_recipient_version');

		$this->createIndex('ophcocorrespondence_cbt_recipient_aid_fk','ophcocorrespondence_cbt_recipient_version','id');
		$this->addForeignKey('ophcocorrespondence_cbt_recipient_aid_fk','ophcocorrespondence_cbt_recipient_version','id','ophcocorrespondence_cbt_recipient','id');

		$this->addColumn('ophcocorrespondence_cbt_recipient_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophcocorrespondence_cbt_recipient_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophcocorrespondence_cbt_recipient_version','version_id');
		$this->alterColumn('ophcocorrespondence_cbt_recipient_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		$this->execute("
CREATE TABLE `ophcocorrespondence_letter_enclosure_version` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`element_letter_id` int(10) unsigned NOT NULL,
	`content` varchar(128) COLLATE utf8_bin DEFAULT NULL,
	`display_order` int(10) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	PRIMARY KEY (`id`),
	KEY `acv_ophcocorrespondence_letter_enclosure_element_letter_id_fk` (`element_letter_id`),
	KEY `acv_ophcocorrespondence_letter_enclosure_lmiu_fk` (`last_modified_user_id`),
	KEY `acv_ophcocorrespondence_letter_enclosure_cu_fk` (`created_user_id`),
	CONSTRAINT `acv_ophcocorrespondence_letter_enclosure_element_letter_id_fk` FOREIGN KEY (`element_letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`),
	CONSTRAINT `acv_ophcocorrespondence_letter_enclosure_lmiu_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `acv_ophcocorrespondence_letter_enclosure_cu_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->alterColumn('ophcocorrespondence_letter_enclosure_version','id','int(10) unsigned NOT NULL');
		$this->dropPrimaryKey('id','ophcocorrespondence_letter_enclosure_version');

		$this->createIndex('ophcocorrespondence_letter_enclosure_aid_fk','ophcocorrespondence_letter_enclosure_version','id');
		$this->addForeignKey('ophcocorrespondence_letter_enclosure_aid_fk','ophcocorrespondence_letter_enclosure_version','id','ophcocorrespondence_letter_enclosure','id');

		$this->addColumn('ophcocorrespondence_letter_enclosure_version','version_date',"datetime not null default '1900-01-01 00:00:00'");

		$this->addColumn('ophcocorrespondence_letter_enclosure_version','version_id','int(10) unsigned NOT NULL');
		$this->addPrimaryKey('version_id','ophcocorrespondence_letter_enclosure_version','version_id');
		$this->alterColumn('ophcocorrespondence_letter_enclosure_version','version_id','int(10) unsigned NOT NULL AUTO_INCREMENT');

		foreach (Yii::app()->db->createCommand()->select("*")->from("et_ophcocorrespondence_letter_old")->order("id asc")->queryAll() as $old_letter) {
			$old_letter['id'] = $old_letter['letter_id'];
			unset($old_letter['letter_id']);

			$this->insert('et_ophcocorrespondence_letter_version', $old_letter);
		}

		$this->dropTable('et_ophcocorrespondence_letter_old');
	}

	public function down()
	{
		$this->execute("
CREATE TABLE `et_ophcocorrespondence_letter_old` (
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`letter_id` int(10) unsigned NOT NULL,
	`use_nickname` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`date` datetime NOT NULL,
	`address` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
	`introduction` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	`re` varchar(1024) COLLATE utf8_bin DEFAULT NULL,
	`body` text COLLATE utf8_bin,
	`footer` varchar(2048) COLLATE utf8_bin DEFAULT NULL,
	`cc` text COLLATE utf8_bin,
	`draft` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
	`created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
	`print` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`locked` tinyint(1) unsigned NOT NULL DEFAULT '0',
	`site_id` int(10) unsigned NOT NULL,
	PRIMARY KEY (`id`),
	KEY `et_ophcocorrespondence_letter_old_letter_id_fk` (`letter_id`),
	KEY `et_ophcocorrespondence_letter_old_last_modified_user_id_fk` (`last_modified_user_id`),
	KEY `et_ophcocorrespondence_letter_old_created_user_id_fk` (`created_user_id`),
	KEY `et_ophcocorrespondence_letter_old_site_id_fk` (`site_id`),
	CONSTRAINT `et_ophcocorrespondence_letter_old_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `et_ophcocorrespondence_letter_old_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
	CONSTRAINT `et_ophcocorrespondence_letter_old_letter_id_fk` FOREIGN KEY (`letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`),
	CONSTRAINT `et_ophcocorrespondence_letter_old_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		foreach (Yii::app()->db->createCommand()->select("*")->from("et_ophcocorrespondence_letter_version")->order("id asc")->queryAll() as $versiond_letter) {
			$versiond_letter['letter_id'] = $versiond_letter['id'];
			unset($versiond_letter['id']);

			foreach (array('direct_line','fax','clinic_date','print_all') as $field) {
				unset($versiond_letter[$field]);
			}

			$this->insert('et_ophcocorrespondence_letter_old', $old_letter);
		}

		$this->dropTable('et_ophcocorrespondence_firm_letter_macro_version');
		$this->dropTable('et_ophcocorrespondence_firm_letter_string_version');
		$this->dropTable('et_ophcocorrespondence_firm_site_secretary_version');
		$this->dropTable('et_ophcocorrespondence_letter_version');
		$this->dropTable('et_ophcocorrespondence_letter_macro_version');
		$this->dropTable('et_ophcocorrespondence_letter_string_version');
		$this->dropTable('et_ophcocorrespondence_letter_string_group_version');
		$this->dropTable('et_ophcocorrespondence_subspecialty_letter_macro_version');
		$this->dropTable('et_ophcocorrespondence_subspecialty_letter_string_version');
		$this->dropTable('ophcocorrespondence_cbt_recipient_version');
		$this->dropTable('ophcocorrespondence_letter_enclosure_version');
	}
}
