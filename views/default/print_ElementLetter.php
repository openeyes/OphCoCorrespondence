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
?>
<p class="accessible">
	<?php echo $element->renderIntroduction()?>
</p>
<p class="accessible"><strong><?php if ($element->re) { ?>Re: <?php echo preg_replace("/\, DOB\:|DOB\:/","<br />\nDOB:",CHtml::encode($element->re))?>
<?php } else { ?>Hosp No: <?php echo $element->event->episode->patient->hos_num?>, NHS No: <?php echo $element->event->episode->patient->nhsnum?> <?php }?></strong></p>

<p class="accessible">
<?php echo $element->renderBody() ?>
</p>
<br/>
<p class="accessible" nobr="true">
	<?php echo $element->renderFooter() ?>
</p>

<?php if ($element->cc) { ?>
<p nobr="true">
	To:
	<?php echo $element->renderToAddress()?>
	<?php foreach (explode("\n",trim(CHtml::encode($element->cc))) as $line) {
			if (trim($line)) { ?>
	<br />CC:
	<?php echo $line ?>
	<?php }
	foreach ($element->enclosures as $enclosure) {?>
		<br/>Enc: <?php echo $enclosure->content?>
	<?php }
} ?>
</p>
<?php } ?>
