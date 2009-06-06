<h2>View PhaHe <?php echo $phahe->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('PhaHe List',array('list')); ?>]
[<?php echo CHtml::link('New PhaHe',array('create')); ?>]
[<?php echo CHtml::link('Update PhaHe',array('update','id'=>$phahe->id)); ?>]
[<?php echo CHtml::linkButton('Delete PhaHe',array('submit'=>array('delete','id'=>$phahe->id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Manage PhaHe',array('admin')); ?>]
</div>

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('name')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->name); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('content')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->content); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('created_on')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->created_on); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('updated_on')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->updated_on); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('created_by_id')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->created_by_id); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($phahe->getAttributeLabel('created_name')); ?>
</th>
    <td><?php echo CHtml::encode($phahe->created_name); ?>
</td>
</tr>
</table>
