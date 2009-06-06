<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($phahe); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'name'); ?>
<?php echo CHtml::activeTextField($phahe,'name',array('size'=>60,'maxlength'=>150)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'content'); ?>
<?php echo CHtml::activeTextArea($phahe,'content',array('rows'=>6, 'cols'=>50)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'created_on'); ?>
<?php echo CHtml::activeTextField($phahe,'created_on'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'updated_on'); ?>
<?php echo CHtml::activeTextField($phahe,'updated_on'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'created_by_id'); ?>
<?php echo CHtml::activeTextField($phahe,'created_by_id'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($phahe,'created_name'); ?>
<?php echo CHtml::activeTextField($phahe,'created_name',array('size'=>60,'maxlength'=>150)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->