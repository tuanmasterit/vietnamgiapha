<h2>View Tree <?php echo $tree->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Tree List',array('list')); ?>]
[<?php echo CHtml::link('New Tree',array('create')); ?>]
[<?php echo CHtml::link('Update Tree',array('update','id'=>$tree->id)); ?>]
[<?php echo CHtml::linkButton('Delete Tree',array('submit'=>array('delete','id'=>$tree->id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Manage Tree',array('admin')); ?>]
</div>

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($tree->getAttributeLabel('lft')); ?>
</th>
    <td><?php echo CHtml::encode($tree->lft); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($tree->getAttributeLabel('rgt')); ?>
</th>
    <td><?php echo CHtml::encode($tree->rgt); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($tree->getAttributeLabel('level')); ?>
</th>
    <td><?php echo CHtml::encode($tree->level); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($tree->getAttributeLabel('name')); ?>
</th>
    <td><?php echo CHtml::encode($tree->name); ?>
</td>
</tr>
</table>
