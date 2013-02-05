<?php $this->header(); ?>

<h3 class="withEventIcon" style="background:transparent url(<?php echo $this->assetPath?>/img/medium.png) center left no-repeat;"><?php echo $this->event_type->name ?></h3>

<div id="event_<?php echo $this->module->name?>">
	<?php
		$form = $this->beginWidget('BaseEventTypeCActiveForm', array(
			'id'=>'clinical-create',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('class'=>'sliding'),
		));
	?>

		<?php if(!$this->patient->practice || !$this->patient->practice->address) { ?>
		<div id="no-practice-address" class="alertBox">
			Patient has no GP practice address, please correct in PAS before creating GP letter.
		</div>
		<?php } ?>

		<?php $this->displayErrors($errors)?>

		<div class="elements">
			<?php $this->renderDefaultElements($this->action->id, $form); ?>
			<?php $this->renderOptionalElements($this->action->id, $form); ?>
		</div>

		<?php $this->displayErrors($errors)?>

		<div class="cleartall"></div>
		<?php
			// Event actions
			$this->event_actions[] = EventAction::button('Save draft', 'savedraft', array('id' => 'et_save_draft', 'colour' => 'green'));
			$this->event_actions[] = EventAction::button('Save and print', 'saveprint', array('id' => 'et_save_print', 'colour' => 'green'));
			$this->renderPartial('//patient/event_actions');
		?>
		<?php $this->endWidget(); ?>
</div>

<div id="dialog-confirm-cancel" title="Cancel">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>All text entered will be lost. Are you sure?</p>
</div>

<?php $this->footer() ?>
