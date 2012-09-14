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
?>

<div id="correspondence_out"<?php if ($element->draft){?> class="draft"<?php }?>>
	<div class="letter_header">
		<?php $this->renderPartial("letter_start", array(
			'site' => $element->site,
			'toAddress' => $element->address,
			'patient' => $this->patient,
			'date' => $element->date,
			'directLine' => $element->direct_line,
		))?>
	</div>

	<?php $this->renderPartial("reply_address", array(
			'site' => $element->site,
	))?>
		
	<div class="big">
	<p>
		<?php echo $element->renderIntroduction()?>
		<br/><br/>
		<?php if ($element->re) {?>
			<strong>Re: <?php echo preg_replace("/\, DOB\:|DOB\:/","<br />\nDOB:",$element->re)?></strong>
			<br/><br/>
		<?php }?>
		<?php echo $element->renderBody()?>
		<br/><br/>
		<?php echo $element->renderFooter()?>
		<br/><br/>
	</p>
	</div>

	<br/>
	
	<p>
		<?php if ($element->cc) {?>
			To: <?php echo $element->renderToAddress()?>
			<?php foreach (explode("\n",trim($element->cc)) as $line) {
				if (trim($line)) { ?>
				<br/>Cc: <?php echo $line ?>
			<?php }?>
			<?php }?>
		<?php }?>
	</p>

	<div class="patron">
		Patron: Her Majesty The Queen<br/>
		Chairman: Rudy Markham<br/>
		Chief Executive: John Pelly
	</div>

	<input type="hidden" name="OphCoCorrespondence_printLetter" id="OphCoCorrespondence_printLetter" value="<?php echo $element->print?>" />
</div>

<div class="correspondence_footer">
	Created on <?php echo $element->event->NHSDate('created_date')?> at <?php echo substr($element->event->created_date,11,5)?> by <?php echo User::model()->findByPk($element->event->created_user_id)->fullnameandtitle?><br/>
	Last modified on <?php echo $element->event->NHSDate('last_modified_date')?> at <?php echo substr($element->event->last_modified_date,11,5)?> by <?php echo User::model()->findByPk($element->event->last_modified_user_id)->fullnameandtitle?><br/>
</div>
