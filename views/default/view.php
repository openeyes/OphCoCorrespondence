<?php
	$this->breadcrumbs=array($this->module->id);
	$this->header();
?>

<h3 class="withEventIcon" style="background:transparent url(<?php echo $this->assetPath?>/img/medium.png) center left no-repeat;"><?php echo $this->event_type->name ?></h3>

<input type="hidden" id="moduleCSSPath" value="<?php echo $this->assetPath?>css" />

<div>
	<?php $this->renderDefaultElements($this->action->id); ?>
	<?php $this->renderOptionalElements($this->action->id); ?>

	<div class="cleartall"></div>
</div>

<div class="form_button">
	<img class="loader" style="display: none;" src="<?php echo Yii::app()->createUrl('img/ajax-loader.gif')?>" alt="loading..." />&nbsp;
	<button type="submit" class="classy blue venti" id="et_print" name="print"><span class="button-span button-span-blue">Print</span></button>
	<button type="submit" class="classy blue venti" id="et_print_all" name="printall"><span class="button-span button-span-blue">Print all</span></button>
</div>

<iframe id="print_iframe" name="print_iframe" style="display: none;" src="<?php echo Yii::app()->createUrl('OphCoCorrespondence/Default/print/'.$this->event->id)?>"></iframe>
<iframe id="print_all_iframe" name="print_all_iframe" style="display: none;" src="<?php echo Yii::app()->createUrl('OphCoCorrespondence/Default/print/'.$this->event->id)?>?all=1"></iframe>

<?php $this->footer() ?>
