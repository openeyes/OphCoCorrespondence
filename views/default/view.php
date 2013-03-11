<?php $this->header(); ?>

<h3 class="withEventIcon"><?php echo $this->event_type->name ?></h3>

<?php
	// Event actions
	$this->event_actions[] = EventAction::button('Print', 'print');
	$this->event_actions[] = EventAction::button('Print all', 'printall', null, array('id' => 'et_print_all'));
	$this->renderPartial('//patient/event_actions');
?>

<input type="hidden" id="moduleCSSPath" value="<?php echo $this->assetPath?>css" />

<div>
	<?php $this->renderDefaultElements($this->action->id); ?>
	<?php $this->renderOptionalElements($this->action->id); ?>

	<div class="cleartall"></div>
</div>

<?php $this->footer() ?>
