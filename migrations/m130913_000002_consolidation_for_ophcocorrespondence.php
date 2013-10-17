cd <?php

class m130913_000002_consolidation_for_ophcocorrespondence extends OEMigration
{
	public function up()
	{

		/*$mr = $this->dbConnection->createCommand()->select('id')->from('subspecialty')->where('ref_spec=:code', array(':code'=>'MR'))->queryRow();
		$mr_id = $mr['id'];

		// Follow up letter macro
		if (!$lm = SubspecialtyLetterMacro::model()->find('name=? and subspecialty_id=?', array('Follow up', $mr_id))) {
			$lm = new SubspecialtyLetterMacro;
		}
		$lm->name = 'Follow up';
		$lm->subspecialty_id = $mr_id;
		$lm->episode_status_id = 5;
		$lm->recipient_patient = 0;
		$lm->recipient_doctor = 1;
		$lm->use_nickname = 1;
		$lm->body = "Diagnosis:
Right eye:    [crd]([nrr]/[nrm])
Left eye:     [cld]([nlr]/[nlm])
[dmt]

Visual acuity: [vbb]
Laser management: [lmp]

Comments: [pro] has been advised of the importance of optimal blood sugar and blood pressure control in reducing the risk of retinopathy and maculopathy worsening. The importance of regular follow-up has been emphasised. Other points: [lmc].
[pro] will be reviewed in [fup]";
		$lm->cc_patient = 1;
		$lm->display_order = 1;
		$lm->save();

		// Discharge letter macro
		if (!$lm = SubspecialtyLetterMacro::model()->find('name=? and subspecialty_id=?', array('Discharge', $mr_id))) {
			$lm = new SubspecialtyLetterMacro;
		}
		$lm->name = 'Discharge';
		$lm->subspecialty_id = $mr_id;
		$lm->episode_status_id = 6;
		$lm->recipient_patient = 0;
		$lm->recipient_doctor = 1;
		$lm->use_nickname = 1;
		$lm->body = "Diagnosis:
Right eye:    [crd]([nrr]/[nrm])
Left eye:     [cld]([nlr]/[nlm])
[dmt]

Visual acuity:  [vbb]
Laser management: [lmp]

Comments: [pro] has been advised of the importance of optimal blood sugar and blood pressure control in reducing the risk of retinopathy and maculopathy worsening. The importance of regular follow-up has been emphasised. Other points: [lmc].
[pro] has been referred to [pos] PCT's diabetic retinopathy screening programme who will review [obj] in one year's time.";
		$lm->cc_patient = 1;
		$lm->display_order = 1;
		$lm->save();*/

		//disable foreign keys check
		$this->execute("SET foreign_key_checks = 0");



		$this->execute("CREATE TABLE `et_ophcocorrespondence_firm_letter_macro` (
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
			  PRIMARY KEY (`id`),
			  KEY `et_ophcocorrespondence_flm_firm_id_fk` (`firm_id`),
			  KEY `et_ophcocorrespondence_flm_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_flm_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_flm_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_flm_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_flm_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_firm_letter_string` (
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
			  KEY `et_ophcocorrespondence_fls_letter_string_group_id_fk` (`letter_string_group_id`),
			  KEY `et_ophcocorrespondence_fls_firm_id_fk` (`firm_id`),
			  KEY `et_ophcocorrespondence_fls_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_fls_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_fls_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fls_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fls_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_firm_site_secretary` (
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
			  KEY `et_ophcocorrespondence_fss_firm_id_fk` (`firm_id`),
			  KEY `et_ophcocorrespondence_fss_site_id_fk` (`site_id`),
			  KEY `et_ophcocorrespondence_fss_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_fss_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_fss_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fss_firm_id_fk` FOREIGN KEY (`firm_id`) REFERENCES `firm` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fss_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_fss_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_letter` (
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
			  KEY `et_ophcocorrespondence_letter_event_id_fk` (`event_id`),
			  KEY `et_ophcocorrespondence_letter_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_letter_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_letter_macro` (
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
			  PRIMARY KEY (`id`),
			  KEY `et_ophcocorrespondence_lm_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_lm_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophcocorrespondence_lm_site_id_fk` (`site_id`),
			  CONSTRAINT `et_ophcocorrespondence_lm_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_lm_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_lm_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_letter_old` (
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
			  CONSTRAINT `et_ophcocorrespondence_letter_old_letter_id_fk` FOREIGN KEY (`letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_old_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_old_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_letter_old_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_letter_string` (
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
			  KEY `et_ophcocorrespondence_ls2_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophcocorrespondence_ls2_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_ls2_letter_string_group_id_fk` (`letter_string_group_id`),
			  KEY `et_ophcocorrespondence_ls2_created_site_id_fk` (`site_id`),
			  CONSTRAINT `et_ophcocorrespondence_ls2_created_site_id_fk` FOREIGN KEY (`site_id`) REFERENCES `site` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_ls2_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_ls2_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_ls2_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_letter_string_group` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
			  `display_order` tinyint(3) unsigned NOT NULL DEFAULT '1',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `et_ophcocorrespondence_lsg_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_lsg_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_lsg_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_lsg_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_subspecialty_letter_macro` (
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
			  PRIMARY KEY (`id`),
			  KEY `et_ophcocorrespondence_slm2_subspecialty_id_fk` (`subspecialty_id`),
			  KEY `et_ophcocorrespondence_slm2_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_slm2_created_user_id_fk` (`created_user_id`),
			  CONSTRAINT `et_ophcocorrespondence_slm2_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_slm2_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_slm2_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `et_ophcocorrespondence_subspecialty_letter_string` (
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
			  KEY `et_ophcocorrespondence_sls_created_user_id_fk` (`created_user_id`),
			  KEY `et_ophcocorrespondence_sls_last_modified_user_id_fk` (`last_modified_user_id`),
			  KEY `et_ophcocorrespondence_sls_letter_string_group_id_fk` (`letter_string_group_id`),
			  KEY `et_ophcocorrespondence_sls_subspecialty_id_fk` (`subspecialty_id`),
			  CONSTRAINT `et_ophcocorrespondence_sls_created_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_sls_last_modified_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_sls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`),
			  CONSTRAINT `et_ophcocorrespondence_sls_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$this->execute("CREATE TABLE `ophcocorrespondence_letter_enclosure` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `element_letter_id` int(10) unsigned NOT NULL,
			  `content` varchar(128) COLLATE utf8_bin DEFAULT NULL,
			  `display_order` int(10) unsigned NOT NULL DEFAULT '0',
			  `last_modified_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `last_modified_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  `created_user_id` int(10) unsigned NOT NULL DEFAULT '1',
			  `created_date` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
			  PRIMARY KEY (`id`),
			  KEY `ophcocorrespondence_letter_enclosure_element_letter_id_fk` (`element_letter_id`),
			  KEY `ophcocorrespondence_letter_enclosure_lmiu_fk` (`last_modified_user_id`),
			  KEY `ophcocorrespondence_letter_enclosure_cu_fk` (`created_user_id`),
			  CONSTRAINT `ophcocorrespondence_letter_enclosure_element_letter_id_fk` FOREIGN KEY (`element_letter_id`) REFERENCES `et_ophcocorrespondence_letter` (`id`),
			  CONSTRAINT `ophcocorrespondence_letter_enclosure_lmiu_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`),
			  CONSTRAINT `ophcocorrespondence_letter_enclosure_cu_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin
		");

		$migrations_path = dirname(__FILE__);
		$this->initialiseData($migrations_path);

		//enable foreign keys check
		$this->execute("SET foreign_key_checks = 1");

	}

	public function down()
	{
		/*$sub_spec = $this->dbConnection->createCommand()->select('id')->from('subspecialty')->where('ref_spec=:ref',array(':ref'=>"MR"))->queryRow();

		// remove the letter macros
		$this->delete('et_ophcocorrespondence_subspecialty_letter_macro', "subspecialty_id=:id", array(":id" => $sub_spec['id']));*/

		$this->execute("SET foreign_key_checks = 0");

		$tables = array(
			'et_ophcocorrespondence_firm_letter_macro',
			'et_ophcocorrespondence_firm_letter_string',
			'et_ophcocorrespondence_firm_site_secretary',
			'et_ophcocorrespondence_letter',
			'et_ophcocorrespondence_letter_macro',
			'et_ophcocorrespondence_letter_old',
			'et_ophcocorrespondence_letter_string',
			'et_ophcocorrespondence_letter_string_group',
			'et_ophcocorrespondence_subspecialty_letter_macro',
			'et_ophcocorrespondence_subspecialty_letter_string',
			'ophcocorrespondence_letter_enclosure'
		);

		foreach ($tables as $table) {
			$this->dropTable($table);
		}
		$this->execute("SET foreign_key_checks = 1");
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
