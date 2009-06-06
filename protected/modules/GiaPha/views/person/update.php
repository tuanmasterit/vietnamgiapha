<h2>Update Person <?php echo $person->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Person List',array('list')); ?>]
[<?php echo CHtml::link('New Person',array('create')); ?>]
[<?php echo CHtml::link('Manage Person',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'person'=>$person,
	'update'=>true,
)); ?>