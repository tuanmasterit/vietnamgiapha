<h2>New Tree</h2>

<div class="actionBar">
[<?php echo CHtml::link('Tree List',array('list')); ?>]
[<?php echo CHtml::link('Manage Tree',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'tree'=>$tree,
	'update'=>false,
)); ?>