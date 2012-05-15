<?php
	$this->breadcrumbs=array($this->module->id);
	$this->header();
?>

<h3 class="withEventIcon" style="background:transparent url(<?php echo $this->imgPath?>medium.png) center left no-repeat;"><?php echo $this->event_type->name ?></h3>

<div>
	<?php $this->renderDefaultElements($this->action->id); ?>
	<?php $this->renderOptionalElements($this->action->id); ?>

	<div class="cleartall"></div>
</div>

<div class="form_button">
	<img class="loader" style="display: none;" src="/img/ajax-loader.gif" alt="loading..." />&nbsp;
	<button type="submit" class="classy blue venti" id="et_print" name="print"><span class="button-span button-span-blue">Print</span></button>
	<button type="submit" class="classy blue venti" id="et_print_all" name="printall"><span class="button-span button-span-blue">Print all</span></button>
	<?php if ($this->editable) {?>
		<button type="submit" class="classy green venti" id="et_confirm_printed" name="confirmprinted"><span class="button-span button-span-green">Confirm printed</span></button>
	<?php }?>
</div>

<?php $this->footer() ?>
