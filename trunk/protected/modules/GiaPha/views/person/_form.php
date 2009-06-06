<div class="yiiForm">

<p>
Fields with <span class="required">*</span> are required.
</p>

<?php echo CHtml::beginForm(); ?>

<?php echo CHtml::errorSummary($person); ?>

<div class="simple">
<?php echo CHtml::activeLabelEx($person,'phahe_id'); ?>
<?php echo CHtml::activeTextField($person,'phahe_id'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'parent_id'); ?>
<?php echo CHtml::activeTextField($person,'parent_id'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'family_id'); ?>
<?php echo CHtml::activeTextField($person,'family_id'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'pid'); ?>
<?php echo CHtml::activeTextField($person,'pid',array('size'=>16,'maxlength'=>16)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'pic'); ?>
<?php echo CHtml::activeTextField($person,'pic',array('size'=>60,'maxlength'=>64)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'sts'); ?>
<?php echo CHtml::activeTextField($person,'sts'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'gender'); ?>
<?php echo CHtml::activeTextField($person,'gender',array('size'=>1,'maxlength'=>1)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'name_thuy'); ?>
<?php echo CHtml::activeTextField($person,'name_thuy',array('size'=>60,'maxlength'=>96)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'name_huy'); ?>
<?php echo CHtml::activeTextField($person,'name_huy',array('size'=>60,'maxlength'=>96)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'name_tu'); ?>
<?php echo CHtml::activeTextField($person,'name_tu',array('size'=>32,'maxlength'=>32)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'name_thuong'); ?>
<?php echo CHtml::activeTextField($person,'name_thuong',array('size'=>32,'maxlength'=>32)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'conthumay'); ?>
<?php echo CHtml::activeTextField($person,'conthumay'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'dob'); ?>
<?php echo CHtml::activeTextField($person,'dob',array('size'=>32,'maxlength'=>32)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'dod'); ?>
<?php echo CHtml::activeTextField($person,'dod',array('size'=>32,'maxlength'=>32)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'wod'); ?>
<?php echo CHtml::activeTextField($person,'wod',array('size'=>60,'maxlength'=>255)); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'huong_tho'); ?>
<?php echo CHtml::activeTextField($person,'huong_tho'); ?>
</div>
<div class="simple">
<?php echo CHtml::activeLabelEx($person,'detail'); ?>
<?php echo CHtml::activeTextArea($person,'detail',array('rows'=>6, 'cols'=>50)); ?>
</div>

<div class="action">
<?php echo CHtml::submitButton($update ? 'Save' : 'Create'); ?>
</div>

<?php echo CHtml::endForm(); ?>

</div><!-- yiiForm -->