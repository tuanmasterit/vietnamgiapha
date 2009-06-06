<h2>Update PhaHe <?php echo $phahe->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('PhaHe List',array('list')); ?>]
[<?php echo CHtml::link('New PhaHe',array('create')); ?>]
[<?php echo CHtml::link('Manage PhaHe',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'phahe'=>$phahe,
	'update'=>true,
)); ?>