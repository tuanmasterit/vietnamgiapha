<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($tree); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($tree,'lft'); ?>
<?php echo CHtml::activeTextField($tree,'lft'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($tree,'rgt'); ?>
<?php echo CHtml::activeTextField($tree,'rgt'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($tree,'level'); ?>
<?php echo CHtml::activeTextField($tree,'level'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($tree,'name'); ?>
<?php echo CHtml::activeTextField($tree,'name',array('size'=>60,'maxlength'=>255)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->