<?php

class m130531_134251_mark_as_support_service_event_type extends CDbMigration
{
	public function up()
	{
		$this->update('event_type',array('support_services'=>1),"class_name = 'OphCoCorrespondence'");
	}

	public function down()
	{
		$this->update('event_type',array('support_services'=>0),"class_name = 'OphCoCorrespondence'");
	}
}
