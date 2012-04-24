<?php

class m120424_102904_correspondence_initial_migration extends CDbMigration
{
	public function up()
	{
		$group = $this->dbConnection->createCommand()->select('id')->from('event_group')->where('name=:name',array(':name'=>'Communication events'))->queryRow();
		$this->insert('event_type', array('name' => 'Correspondence','event_group_id' => $group['id'], 'class_name' => 'OphCoCorrespondence'));

		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('event_group_id=:event_group_id and class_name=:class_name',array(':event_group_id'=>$group['id'],':class_name'=>'OphCoCorrespondence'))->queryRow();

		$this->insert('element_type', array('name' => 'Letter','class_name' => 'ElementLetter', 'event_type_id' => $event_type['id'], 'display_order' => 1));

		$this->createTable('et_ophcocorrespondence_letter', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'event_id' => 'int(10) unsigned NOT NULL',
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
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_letter_event_id_fk` (`event_id`)',
				'KEY `et_ophcocorrespondence_letter_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_letter_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_event_id_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->createTable('et_ophcocorrespondence_letter_macro', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
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
				'KEY `et_ophcocorrespondence_letter_macro_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_letter_macro_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_macro_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_letter_macro_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophcocorrespondence_letter_macro',array('name'=>'Referral','recipient_doctor'=>1,'body'=>'Thank you very much for referring this [age] year old [sub] who I saw in the clinic today.','cc_patient'=>1,'display_order'=>1));

		$this->createTable('et_ophcocorrespondence_letter_string_group', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_lsg_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_lsg_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_lsg_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_lsg_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>1,'name'=>'Introduction','display_order'=>1));
		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>2,'name'=>'Findings','display_order'=>2));
		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>3,'name'=>'Diagnosis','display_order'=>3));
		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>4,'name'=>'Management','display_order'=>4));
		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>5,'name'=>'Drugs','display_order'=>5));
		$this->insert('et_ophcocorrespondence_letter_string_group',array('id'=>6,'name'=>'Outcome','display_order'=>6));

		$this->createTable('et_ophcocorrespondence_letter_string', array(
				'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
				'letter_string_group_id' => 'int(10) unsigned NOT NULL',
				'subspecialty_id' => 'int(10) unsigned NOT NULL',
				'name' => 'varchar(64) COLLATE utf8_bin DEFAULT NULL',
				'body' => 'text COLLATE utf8_bin DEFAULT NULL',
				'display_order' => 'tinyint(3) unsigned NOT NULL DEFAULT 1',
				'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'last_modified_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT \'1\'',
				'created_date' => 'datetime NOT NULL DEFAULT \'1900-01-01 00:00:00\'',
				'PRIMARY KEY (`id`)',
				'KEY `et_ophcocorrespondence_ls_letter_string_group_id_fk` (`letter_string_group_id`)',
				'KEY `et_ophcocorrespondence_ls_subspecialty_id_fk` (`subspecialty_id`)',
				'KEY `et_ophcocorrespondence_ls_last_modified_user_id_fk` (`last_modified_user_id`)',
				'KEY `et_ophcocorrespondence_ls_created_user_id_fk` (`created_user_id`)',
				'CONSTRAINT `et_ophcocorrespondence_ls_letter_string_group_id_fk` FOREIGN KEY (`letter_string_group_id`) REFERENCES `et_ophcocorrespondence_letter_string_group` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_ls_subspecialty_id_fk` FOREIGN KEY (`subspecialty_id`) REFERENCES `subspecialty` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_ls_last_modified_user_id_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
				'CONSTRAINT `et_ophcocorrespondence_ls_created_user_id_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)'
			),
			'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin'
		);

		if ($specialty = $this->dbConnection->createCommand()->select('id')->from('specialty')->where('code=:code', array(':code'=>'OPH'))->queryRow()) {
			if ($subspecialty = $this->dbConnection->createCommand()->select('id')->from('subspecialty')->where('specialty_id=:specialty_id and ref_spec=:ref_spec', array(':specialty_id'=>$specialty['id'],':ref_spec'=>'CA'))->queryRow()) {

				/* example data, obviously this isn't real */

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>1,'subspecialty_id'=>$subspecialty['id'],'name'=>'Referral','body'=>'Referral text','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>1,'subspecialty_id'=>$subspecialty['id'],'name'=>'Invitation','body'=>'Introduction text','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>1,'subspecialty_id'=>$subspecialty['id'],'name'=>'Follow-up','body'=>'Follow-up text','display_order'=>3));

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>2,'subspecialty_id'=>$subspecialty['id'],'name'=>'All good','body'=>'I examined this [age] year old [sub] and found everything was good.','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>2,'subspecialty_id'=>$subspecialty['id'],'name'=>'Possibly a bit shaky','body'=>'I examined this [age] year old [sub] and found their eyes were a bit wonky','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>2,'subspecialty_id'=>$subspecialty['id'],'name'=>'Golly, that\'s not good','body'=>'I examined this [age] year old [sub] and found one of their eyes was missing.','display_order'=>3));

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>3,'subspecialty_id'=>$subspecialty['id'],'name'=>'Happy','body'=>'vi','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>3,'subspecialty_id'=>$subspecialty['id'],'name'=>'Sad','body'=>'coda/emacs/textmate','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>3,'subspecialty_id'=>$subspecialty['id'],'name'=>'Indifferent','body'=>'nano','display_order'=>3));

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>4,'subspecialty_id'=>$subspecialty['id'],'name'=>'Commitee','body'=>'Oh dear, this [age] year old [sub] called a committee meeting.','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>4,'subspecialty_id'=>$subspecialty['id'],'name'=>'Board','body'=>'Oh dear, this [age] year old [sub] called a board meeting.','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>4,'subspecialty_id'=>$subspecialty['id'],'name'=>'Silver Command','body'=>'Oh dear, this [age] year old [sub] called a silver command meeting.','display_order'=>3));

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>5,'subspecialty_id'=>$subspecialty['id'],'name'=>'Drugs set 1','body'=>'Prescribed X and Y for this [age] year old [sub].','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>5,'subspecialty_id'=>$subspecialty['id'],'name'=>'Drugs set 2','body'=>'Prescribed X and Y for this [age] year old [sub].','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>5,'subspecialty_id'=>$subspecialty['id'],'name'=>'Ibogaine','body'=>'Prescribed X and Y for this [age] year old [sub].','display_order'=>3));

				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>6,'subspecialty_id'=>$subspecialty['id'],'name'=>'Chirpy','body'=>'The [age] year old [sub] woke up feeling quite chirpy.','display_order'=>1));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>6,'subspecialty_id'=>$subspecialty['id'],'name'=>'Hazy','body'=>'The [age] year old [sub] woke up feeling a bit hazy and lightheaded. Recommended regular trips to Starbucks.','display_order'=>2));
				$this->insert('et_ophcocorrespondence_letter_string',array('letter_string_group_id'=>6,'subspecialty_id'=>$subspecialty['id'],'name'=>'Grumpy','body'=>'The [age] year old [sub] woke up feeling grumpy.','display_order'=>3));
			}
		}
	}

	public function down()
	{
		$this->dropTable('et_ophcocorrespondence_letter_string');
		$this->dropTable('et_ophcocorrespondence_letter_string_group');
		$this->dropTable('et_ophcocorrespondence_letter_macro');
		$this->dropTable('et_ophcocorrespondence_letter');

    $group = $this->dbConnection->createCommand()->select('id')->from('event_group')->where('name=:name',array(':name'=>'Communication events'))->queryRow();

    $event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('event_group_id=:event_group_id and class_name=:class_name',array(':event_group_id'=>$group['id'],':class_name'=>'OphCoCorrespondence'))->queryRow();

		$this->delete('element_type','event_type_id='.$event_type['id']);
		$this->delete('event','event_type_id='.$event_type['id']);
		$this->delete('event_type','id='.$event_type['id']);
	}
}
