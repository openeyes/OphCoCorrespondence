<?php

/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
class ReportLettersTest extends CDbTestCase
{
	public $fixtures = array(
		'events' => 'Event',
		'event_types' => 'EventType',
	);

	public $custom_fixtures = array(
		'et_ophcocorrespondence_letter' => array(
			array(
				'id' => 1,
				'event_id' => 
		),
	);

	public function testAfterValidate_Phrases_Empty()
	{
		$r = new ReportLetters;

		$r->validate();

		$this->assertTrue(isset($r->errors['phrases']));
		$this->assertEquals(array('Phrases cannot be blank.'),$r->errors['phrases']);
	}

	public function testAfterValidate_Phrases_OnlyBlankItems()
	{
		$r = new ReportLetters;

		$r->phrases = array('','','');

		$r->validate();

		$this->assertTrue(isset($r->errors['phrases']));
		$this->assertEquals(array('Phrases cannot be blank.'),$r->errors['phrases']);
	}

	public function testAfterValidate_Phrases_HasData()
	{
		$r = new ReportLetters;

		$r->phrases = array('one','');

		$r->validate();

		$this->assertFalse(isset($r->errors['phrases']));
	}

	public function testAfterValidate_NoType()
	{
		$r = new ReportLetters;

		$r->validate();

		$this->assertTrue(isset($r->errors['match_correspondence']));
		$this->assertEquals(array('Please select which type of letters you want to search'),$r->errors['match_correspondence']);
	}

	public function testAfterValidate_CorrespondenceOnly()
	{
		$r = new ReportLetters;
		$r->match_correspondence = 1;

		$r->validate();

		$this->assertFalse(isset($r->errors['match_correspondence']));
		$this->assertFalse(isset($r->errors['match_legacy_letters']));
	}

	public function testAfterValidate_LegacyOnly()
	{
		$r = new ReportLetters;
		$r->match_legacy_letters = 1;

		$r->validate();

		$this->assertFalse(isset($r->errors['match_correspondence']));
		$this->assertFalse(isset($r->errors['match_legacy_letters']));
	}

	public function testAfterValidate_Both()
	{
		$r = new ReportLetters;
		$r->match_correspondence = 1;
		$r->match_legacy_letters = 1;

		$r->validate();

		$this->assertFalse(isset($r->errors['match_correspondence']));
		$this->assertFalse(isset($r->errors['match_legacy_letters']));
	}

	public function testRun_MatchCorrespondence_JoinApplied()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$r->expects($this->once())
			->method('joinLetters')
			->with(
				'Correspondence',
				$cmd,
				array('c.first_name','c.last_name','p.dob','p.hos_num','e.created_date','ep.patient_id'),
				array(),
				array(),
				' or '
			);

		$r->condition_type = 'or';
		$r->match_correspondence = 1;

		$r->run();
	}

	public function testRun_MatchLegacy_JoinApplied()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();
			
		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$r->expects($this->once())
			->method('joinLetters')
			->with(
				'Legacy',
				$cmd,
				array('c.first_name','c.last_name','p.dob','p.hos_num','e.created_date','ep.patient_id'),
				array(),
				array(),
				' or '
			);
			
		$r->condition_type = 'or';
		$r->match_legacy_letters = 1;

		$r->run();
	}

	public function testRun_MatchCorrespondenceAndLegacy_BothJoinsApplied()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$r->expects($this->at(1))
			->method('joinLetters')
			->with(
				'Correspondence',
				$cmd,
				array('c.first_name','c.last_name','p.dob','p.hos_num','e.created_date','ep.patient_id'),
				array(),
				array(),
				' or '
			);

		$r->expects($this->at(2))
			->method('joinLetters')
			->with(
				'Legacy',
				$cmd,
				array('c.first_name','c.last_name','p.dob','p.hos_num','e.created_date','ep.patient_id'),
				array(),
				array(),
				' or '
			);

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->match_legacy_letters = 1;

		$r->run();
	}

	public function testRun_NoStartDate_DontApplyStartDate()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyStartDate'))
			->getMock();

		$r->expects($this->never())
			->method('applyStartDate');

		$r->condition_type = 'or';
		$r->match_correspondence = 1;

		$r->run();
	}

	public function testRun_StartDate_ApplyStartDate()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyStartDate'))
			->getMock();

		$r->expects($this->once())
			->method('applyStartDate')
			->with(' ('.' '.' '.') ',array());

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->start_date = '11 Jun 2012';

		$r->run();
	}

	public function testRun_StartDate_DontApplyEndDate()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyStartDate','applyEndDate'))
			->getMock();

		$r->expects($this->once())
			->method('applyStartDate')
			->with(' ('.' '.' '.') ',array());

		$r->expects($this->never())
			->method('applyEndDate');

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->start_date = '11 Jun 2012';

		$r->run();
	}

	public function testRun_NoEndDate_DontApplyEndDate()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyEndDate'))
			->getMock();

		$r->expects($this->never())
			->method('applyEndDate');

		$r->condition_type = 'or';
		$r->match_correspondence = 1;

		$r->run();
	}

	public function testRun_EndDate_ApplyEndDate()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyStartDate','applyEndDate'))
			->getMock();
			
		$r->expects($this->never())
			->method('applyStartDate');
		
		$r->expects($this->once())
			->method('applyEndDate')
			->with(' ('.' '.' '.') ',array());

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->end_date = '11 Jun 2012';

		$r->run();
	}

	public function testRun_BothDates_ApplyBothDates()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('joinLetters','executeQuery','applyStartDate','applyEndDate'))
			->getMock();

		$r->expects($this->once())
			->method('applyStartDate')
			->with(' ('.' '.' '.') ',array());

		$r->expects($this->once())
			->method('applyEndDate')
			->with(' ('.' '.' '.') ',array());

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->start_date = '11 Jan 2012';
		$r->end_date = '11 Jun 2012';

		$r->run();
	}

	public function testRun_WhereClause()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$cmd->expects($this->once())
			->method('where')
			->with(' ('.' '.' ) '.' and e.created_date >= :dateFrom',array(':dateFrom' => '2012-06-11 00:00:00'));

		$r->condition_type = 'or';
		$r->match_correspondence = 1;
		$r->start_date = '11 Jun 2012';

		$r->run();
	}

	public function testRun_SelectClause()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$cmd->expects($this->once())
			->method('select')
			->with('c.first_name,c.last_name,p.dob,p.hos_num,e.created_date,ep.patient_id');

		$r->condition_type = 'or';
		$r->match_correspondence = 1;

		$r->run();
	}

	public function testRun_ExecuteQuery()
	{
		$r = $this->getMockBuilder('ReportLetters')
			->disableOriginalConstructor()
			->setMethods(array('getDbCommand','joinLetters','executeQuery'))
			->getMock();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$r->expects($this->once())
			->method('getDbCommand')
			->will($this->returnValue($cmd));

		$r->expects($this->once())
			->method('executeQuery')
			->with($cmd);

		$r->condition_type = 'or';
		$r->match_correspondence = 1;

		$r->run();
	}

	public function testGetDbCommand()
	{
		$r = new ReportLetters;

		$cmd = $r->getDbCommand();

		$this->assertInstanceOf('CDbCommand',$cmd);
		$this->assertEquals('`event` `e`',$cmd->from);
		$this->assertCount(3,$cmd->join);
		$this->assertEquals('JOIN `episode` `ep` ON e.episode_id = ep.id',$cmd->join[0]);
		$this->assertEquals('JOIN `patient` `p` ON ep.patient_id = p.id',$cmd->join[1]);
		$this->assertEquals('JOIN `contact` `c` ON p.contact_id = c.id',$cmd->join[2]);
		$this->assertEquals('`e`.`created_date` ASC',$cmd->order);
	}

	public function testJoinLetters_Correspondence_Join()
	{
		$r = new ReportLetters;

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$cmd->expects($this->once())
			->method('leftJoin')
			->with('et_ophcocorrespondence_letter l','l.event_id = e.id');

		$select = array();
		$where_clauses = array();
		$where_params = array();

		$r->phrases = array('diagnosed','appointment');
		$r->joinLetters('Correspondence',$cmd,$select,$where_clauses,$where_params,' or ');
	}

	public function testJoinLetters_Correspondence_Select()
	{
		$r = new ReportLetters;

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();
			
		$select = array();
		$where_clauses = array();
		$where_params = array();

		$r->phrases = array('diagnosed','appointment');
		$r->joinLetters('Correspondence',$cmd,$select,$where_clauses,$where_params,' or ');

		$this->assertCount(2,$select);
		$this->assertEquals('l.id as lid',$select[0]);
		$this->assertEquals('l.event_id',$select[1]);
	}

	public function testJoinLetters_Correspondence_WhereClauses()
	{
		$r = new ReportLetters;

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$select = array();
		$where_clauses = array();
		$where_params = array();

		$r->phrases = array('diagnosed','appointment');
		$r->joinLetters('Correspondence',$cmd,$select,$where_clauses,$where_params,' or ');

		$this->assertCount(1,$where_clauses);
		$this->assertEquals('(l.id is not null and e.event_type_id = :et_l_id and ( '.' lower(l.body) like :bodyl0 or '.' lower(l.body) like :bodyl1 ) )',$where_clauses[0]);
	}

	public function testJoinLetters_Correspondence_WhereParams()
	{
		$r = new ReportLetters;
	 
		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$select = array();
		$where_clauses = array();
		$where_params = array();

		$r->phrases = array('diagnosed','appointment');
		$r->joinLetters('Correspondence',$cmd,$select,$where_clauses,$where_params,' or ');

		$this->assertCount(3,$where_params);
		$this->assertEquals(1007,$where_params[':et_l_id']);
		$this->assertEquals('%diagnosed%',$where_params[':bodyl0']);
		$this->assertEquals('%appointment%',$where_params[':bodyl1']);
	}

	public function testApplyStartDate()
	{
		$r = new ReportLetters;

		$r->start_date = '11 Jan 2012';

		$where = 'testing123';
		$where_params = array('some','stuff');

		$r->applyStartDate($where,$where_params);

		$this->assertEquals('testing123 and e.created_date >= :dateFrom',$where);
		$this->assertCount(3,$where_params);
		$this->assertEquals('2012-01-11 00:00:00',$where_params[':dateFrom']);
	}

	public function testApplyEndDate()
	{
		$r = new ReportLetters;

		$r->end_date = '11 Oct 2012';

		$where = 'testing456';
		$where_params = array();

		$r->applyEndDate($where,$where_params);

		$this->assertEquals('testing456 and e.created_date <= :dateTo',$where);
		$this->assertCount(1,$where_params);
		$this->assertEquals('2012-10-11 23:59:59',$where_params[':dateTo']);
	}

	public function testExecuteQuery()
	{
		$r = new ReportLetters;
		$r->letters = array();

		$cmd = $this->getMockBuilder('CDbCommand')
			->disableOriginalConstructor()
			->setMethods(array('select','where','join','leftJoin','queryAll'))
			->getMock();

		$cmd->expects($this->once())
			->method('queryAll')
			->will($this->returnValue(array(
				array(
					'lid' => 123,
					'event_id' => 345,
				),
				array(
					'l2id' => 789,
					'l2_event_id' => 707,
				),
			)));

		$r->executeQuery($cmd);

		$this->assertCount(2,$r->letters);

		$this->assertEquals(123,$r->letters[0]['lid']);
		$this->assertEquals(345,$r->letters[0]['event_id']);
		$this->assertEquals('Correspondence',$r->letters[0]['type']);
		$this->assertEquals('http:///OphCoCorrespondence/default/view/345',$r->letters[0]['link']);

		$this->assertEquals(789,$r->letters[1]['l2id']);
		$this->assertEquals(707,$r->letters[1]['l2_event_id']);
		$this->assertEquals('Legacy letter',$r->letters[1]['type']);
		$this->assertEquals('http:///OphLeEpatientletter/default/view/707',$r->letters[1]['link']);
	}

	public function loadFixture($name)
	{
		$this->dbConnection->createCommand("update event set deleted=1")->query();
	}

	public function testRun_Correspondence_AnyPhrase()
	{
		if ($et = EventType::model()->find('class_name=?',array('OphCoCorrespondence'))) {
			$this->loadFixture($et);

			$r = new ReportLetters;
			$r->condition_type = 'or';
			$r->match_correspondence = 1;
			$r->phrases = array('diagnosed');
			$r->start_date = '1 Jan 2012';
			$r->end_date = '31 Dec 2014';

			$r->run();

			print_r($r->letters);
		}
	}

	public function testDescription()
	{
	}

	public function testToCSV()
	{
	}
}
