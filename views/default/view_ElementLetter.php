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
	<?php $this->renderPartial("letter_start", array(
		'site' => $element->site,
		'toAddress' => $element->address,
		'patient' => $this->patient,
		'date' => $element->date,
	))?>

	<br/><br/>

	<p>
		<?php echo str_replace("\n","<br/>",trim($element->introduction))?>
		<br/><br/>
		<strong>Re: <?php echo $element->re?></strong>
		<br/><br/>
		<?php echo str_replace("\n","<br/>",$element->body)?>
		<br/><br/>
		<?php echo str_replace("\n","<br/>",$element->footer)?>
		<br/><br/>
	</p>

	<p>
		To: <?php echo preg_replace('/[\r\n]+/',', ',$element->address)?><br/>
		<?php foreach (explode("\n",trim($element->cc)) as $line) {
			$line = preg_replace('/^cc:[\s\t]+/','',$line);?>
			cc: <?php echo $line?><br />
		<?php }?>
	</p>

	<input type="hidden" name="OphCoCorrespondence_printLetter" id="OphCoCorrespondence_printLetter" value="<?php echo $element->print?>" />
</div>
