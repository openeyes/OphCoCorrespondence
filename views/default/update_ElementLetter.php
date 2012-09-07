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

<div class="element <?php echo $element->elementType->class_name?>"
	data-element-type-id="<?php echo $element->elementType->id ?>"
	data-element-type-class="<?php echo $element->elementType->class_name ?>"
	data-element-type-name="<?php echo $element->elementType->name ?>"
	data-element-display-order="<?php echo $element->elementType->display_order ?>">
	<h4 class="elementTypeName"><?php echo $element->elementType->name ?></h4>

	<script type="text/javascript"> var patient_id = <?php echo $this->patient->id?>; </script>

	<?php echo $form->hiddenInput($element, 'draft', 1)?>

	<div class="row">
		<span class="left"></span>
		<span class="right">
			<?php echo $form->dropDownList($element, 'site_id', Site::model()->getLongListForCurrentInstitution(), array('nowrapper'=>true))?>
		</span>
	</div>

	<div class="row">
		<span class="left">
			<?php echo $form->dropDownListNoPost('address_target', $element->address_targets, $element->address_target, array('empty' => '- Recipient -', 'nowrapper' => true))?>
		</span>
		<span class="right">
			<?php echo $form->textArea($element, 'address', array('rows' => 7, 'cols' => 55, 'label' => false, 'nowrapper' => true))?>
		</span>
	</div>

	<div class="row">
		<span class="left">
			<?php echo $form->dropDownListNoPost('macro', $element->letter_macros, '', array('empty' => '- Macro -', 'nowrapper' => true))?>
		</span>
		<span class="right">
			<?php echo $form->datePicker($element, 'date', array('maxDate' => 'today'), array('nowrapper'=>true))?>
		</span>
	</div>

	<div class="row">
		<span class="left"></span>
		<span class="right">
			<?php echo $form->textArea($element, 'introduction', array('rows' => 2, 'cols' => 55, 'label' => false, 'nowrapper' => true))?>
			<?php echo $form->checkBox($element, 'use_nickname', array('nowrapper' => true))?>
			<?php echo $element->getAttributeLabel('use_nickname')?>
		</span>
	</div>

	<div class="row">
		<span class="left"></span>
		<span class="right">
			<?php echo $form->textArea($element, 're', array('rows' => 2, 'cols' => 100, 'label' => false, 'nowrapper' => true))?>
		</span>
	</div>

	<div class="row">
		<span class="left">
			<?php foreach ($element->stringgroups as $string_group) {?>
				<?php if ($string_group->name == 'Findings') {?>
					<?php echo $form->dropDownListNoPost(strtolower($string_group->name), $string_group->strings, '', array('empty' => '- '.$string_group->name.' -', 'nowrapper' => true, 'class' => 'stringgroup', 'title' => 'Findings will be available with the upcoming Examination module', 'disabled' => true))?>
				<?php }else{?>
					<?php echo $form->dropDownListNoPost(strtolower($string_group->name), $string_group->strings, '', array('empty' => '- '.$string_group->name.' -', 'nowrapper' => true, 'class' => 'stringgroup'))?>
				<?php }?>
			<?php }?>
		</span>
		<span class="right">
			<?php echo $form->textArea($element, 'body', array('rows' => 20, 'cols' => 100, 'label' => false, 'nowrapper' => true))?>
		</span>
	</div>

	<div class="eventDetail row">
		<div class="label OphCoCorrespondence_footerLabel">From:</div>
		<span class="right">
			<div>
				<?php
					$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
						'id'=>'OphCoCorrespondence_footerAutoComplete',
						'name'=>'OphCoCorrespondence_footerAutoComplete',
						'value'=>'',
						'sourceUrl'=>array('default/users'),
						'options'=>array(
							'minLength'=>'3',
							'select'=>"js:function(event, ui) {
								$('#ElementLetter_footer').val(\"Yours sincerely\\n\\n\\n\\n\\n\"+ui.item.fullname+\"\\n\"+ui.item.role+\"\\n\"+(ui.item.consultant?\"Consultant\\n\"+ui.item.consultant:''));
								$('#OphCoCorrespondence_footerAutoComplete').val('');
								return false;
							}",
						),
						'htmlOptions'=>array(
							'style'=>'width: 320px;',
							'placeholder' => 'type to search for users'
						),
					));
				?>
			</div>
			<div id="OphCoCorrespondence_footerDiv">
				<?php echo $form->textArea($element, 'footer', array('rows' => 9, 'cols' => 55, 'label' => false, 'nowrapper' => true))?>
			</div>
		</span>
	</div>

	<div class="row">
		<span class="left">
			<?php echo $form->dropDownListNoPost('cc', $element->address_targets, '', array('empty' => '- Cc -', 'nowrapper' => true))?>
		</span>
		<span class="right">
			<?php echo $form->textArea($element, 'cc', array('rows' => 8, 'cols' => 100, 'label' => false, 'nowrapper' => true))?>
		</span>
		<div id="cc_targets">
		</div>
	</div>
</div>
