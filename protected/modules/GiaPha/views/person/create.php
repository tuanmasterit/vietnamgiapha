<h2>New Person</h2>

<div class="actionBar">
[<?php echo CHtml::link('Person List',array('list')); ?>]
[<?php echo CHtml::link('Manage Person',array('admin')); ?>]
</div>

<?php echo $this->renderPartial('_form', array(
	'person'=>$person,
	'update'=>false,
)); ?>