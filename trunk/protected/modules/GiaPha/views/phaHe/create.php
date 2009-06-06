<h2>New PhaHe</h2>

<div class="actionBar">
[<?php echo CHtml::link('PhaHe List',array('list')); ?>]
[<?php echo CHtml::link('Manage PhaHe',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'phahe'=>$phahe,
	'update'=>false,
)); ?>