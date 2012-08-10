<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
$site_replyto_remap = array(
	10 => array(
		'address' => array(
			'Moorfields at St Georges',
			'St George\'s NHS Trust',
			'Blackshaw Road',
			'Tooting',
			'London SW17 0QT',
		),
		'telephone' => '020 8725 5877',
	),
	15 => array(
		'address' => array(
			'Moorfields at Barking',
			'Satellite Service Office, 3rd floor',
			'Moorfields Eye Hospital ',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2744',
	),
	2 => array(
		'address' => array(
			'Moofields at Bedford',
			'Bedford Hospital NHS Trust - South Wing',
			'Kempston Road',
			'Bedford',
			'MK42 9DJ',
		),
		'telephone' => '01234 792 643',
	),
	11 => array(
		'address' => array(
			'Moorfields at Northwick Park',
			'Northwick Park Hospital',
			'Watford Road',
			'Harrow',
			'Middlesex',
			'HA1 3UJ',
		),
		'telephone' => '020 3182 4000',
	),
	17 => array(
		'address' => array(
			'Moorfields at St Georges',
			'St George\'s NHS Trust',
			'Blackshaw Road',
			'Tooting',
			'London SW17 0QT',
		),
		'telephone' => '020 8401 3486',
	),
	3 => array(
		'address' => array(
			'Moorfields at Ealing Hospital',
			'Uxbridge Road',
			'Southall',
			'Middlesex',
			'UB1 3HW',
		),
		'telephone' => '020 8967 5766',
	),
	18 => array(
		'address' => array(
			'Moorfields Eye Hospital',
			'162 City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2267',
	),
	12 => array(
		'address' => array(
			'Moorfields at Loxford',
			'Satellite Service Office, 3rd floor',
			'Moorfields Eye Hospital ',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2744',
	),
	6 => array(
		'address' => array(
			'Moorfields at Mile End',
			'Satellite service office, 3rd floor',
			'Moorfields Eye Hospital',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2020',
	),
	4 => array(
		'address' => array(
			'Moorfields at Northwick Park',
			'Northwick Park Hospital',
			'Watford Road',
			'Harrow',
			'Middlesex',
			'HA1 3UJ',
		),
		'telephone' => '020 3182 4000',
	),
	7 => array(
		'address' => array(
			'Moorfields at Potters Bar',
			'Satellite Service Office, 3rd floor',
			'Moorfields Eye Hospital ',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2339',
	),
	19 => array(
		'address' => array(
			'General Management Offices',
			'Moorfields Eye Hospital',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2267',
	),
	9 => array(
		'address' => array(
			'Moorfields at St Ann\'s',
			'Satellite service office, 3rd floor',
			'Moorfields Eye Hospital ',
			'City Road',
			'London',
			'EC1V 2PD',
		),
		'telephone' => '020 7566 2841',
	),
	5 => array(
		'address' => array(
			'Moorfields at St Georges',
			'St George\'s NHS Trust',
			'Blackshaw Road',
			'Tooting',
			'London SW17 0QT',
		),
		'telephone' => '020 8725 5877',
	),
	14 => array(
		'address' => array(
			'Moorfields at St Georges',
			'St George\'s NHS Trust',
			'Blackshaw Road',
			'Tooting',
			'London SW17 0QT',
		),
		'telephone' => '020 87251794',
	),
	16 => array(
		'address' => array(
			'Moorfields at Northwick Park',
			'Northwick Park Hospital',
			'Watford Road',
			'Harrow',
			'Middlesex',
			'HA1 3UJ',
		),
		'telephone' => '020 3182 4000',
	),
	20 => array(
		'address' => array(
			'Moorfields at Northwick Park',
			'Northwick Park Hospital',
			'Watford Road',
			'Harrow',
			'Middlesex',
			'HA1 3UJ',
		),
		'telephone' => '020 3182 4000',
	),
	8 => array(
		'address' => array(
			'Moorfields at St Georges',
			'St George\'s NHS Trust',
			'Blackshaw Road',
			'Tooting',
			'London SW17 0QT',
		),
		'telephone' => '020 8725 5877',
	),
);
?>
<div class="banner clearfix">
	<div class="seal"><img src="/img/_print/letterhead_seal.jpg" alt="letterhead_seal" /></div>
	<div class="logo"><img src="/img/_print/letterhead_Moorfields_NHS.jpg" alt="letterhead_Moorfields_NHS" /></div>
</div>
<?php if (isset($site)) {?>
	<div class="fromAddress">
		<?php echo $site->letterhtml ?>
		<br />Tel: <?php echo CHtml::encode($site->telephone) ?>
		<?php if($site->fax) { ?>
		<br />Fax: <?php echo CHtml::encode($site->fax) ?>
		<?php } ?>
		<?php if (isset($site_replyto_remap[$site->id])) {?>
			<br/><br/>
			Please reply to:<br/><br/>
			<?php echo implode('<br/>',$site_replyto_remap[$site->id]['address'])?>
			<?php if (isset($site_replyto_remap[$site->id]['telephone'])) {?>
				<br/>Tel: <?php echo $site_replyto_remap[$site->id]['telephone']?>
			<?php }?>
		<?php }?>
	</div>
<?php }?>
<div class="toAddress"><?php echo $toAddress?></div>
<div class="date"><?php echo date(Helper::NHS_DATE_FORMAT,strtotime($date)) ?></div>
