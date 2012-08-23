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
<div class="banner clearfix">
	<div class="seal"><img src="/img/_print/letterhead_seal.jpg" alt="letterhead_seal" /></div>
	<div class="logo"><img src="/img/_print/letterhead_Moorfields_NHS.jpg" alt="letterhead_Moorfields_NHS" /></div>
</div>
<?php if (isset($site)) {?>
	<div class="fromAddress">
		<?php
		if (isset($site_replyto_remap[$site->id]['site_name'])) {
			$site->name = $site_replyto_remap[$site->id]['site_name'];
		}
		echo $site->letterhtml ?>
		<br />Tel: <?php echo CHtml::encode($site->telephone) ?>
		<?php if($site->fax) { ?>
		<br />Fax: <?php echo CHtml::encode($site->fax) ?>
		<?php } ?>
		<?php if ($directLine) {?>
		<br />Direct line: <?php echo $directLine?>
		<?php }?>
		<div class="date"><?php echo date(Helper::NHS_DATE_FORMAT,strtotime($date)) ?></div>
	</div>
<?php }?>
<div class="toAddress"><?php echo $toAddress?></div>
