<h2>Update Tree <?php echo $tree->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Tree List',array('list')); ?>]
[<?php echo CHtml::link('New Tree',array('create')); ?>]
[<?php echo CHtml::link('Manage Tree',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'tree'=>$tree,
	'update'=>true,
)); ?>