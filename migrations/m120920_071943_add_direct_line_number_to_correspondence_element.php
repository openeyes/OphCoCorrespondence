<?php

class m120920_071943_add_direct_line_number_to_correspondence_element extends CDbMigration
{
	public function up()
	{
		$this->addColumn('et_ophcocorrespondence_letter','direct_line','varchar(32) COLLATE utf8_bin');
	}

	public function down()
	{
		$this->dropColumn('et_ophcocorrespondence_letter','direct_line');
	}
}
