<h2>View Person <?php echo $person->id; ?></h2>

<div class="actionBar">
[<?php echo CHtml::link('Person List',array('list')); ?>]
[<?php echo CHtml::link('New Person',array('create')); ?>]
[<?php echo CHtml::link('Update Person',array('update','id'=>$person->id)); ?>]
[<?php echo CHtml::linkButton('Delete Person',array('submit'=>array('delete','id'=>$person->id),'confirm'=>'Are you sure?')); ?>
]
[<?php echo CHtml::link('Manage Person',array('admin')); ?>]
</div>

<table class="dataGrid">
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('phahe_id')); ?>
</th>
    <td><?php echo CHtml::encode($person->phahe_id); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('family_id')); ?>
</th>
    <td><?php echo CHtml::encode($person->family_id); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('pid')); ?>
</th>
    <td><?php echo CHtml::encode($person->pid); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('pic')); ?>
</th>
    <td><?php echo CHtml::encode($person->pic); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('sts')); ?>
</th>
    <td><?php echo CHtml::encode($person->sts); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('gender')); ?>
</th>
    <td><?php echo CHtml::encode($person->gender); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('name_thuy')); ?>
</th>
    <td><?php echo CHtml::encode($person->name_thuy); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('name_huy')); ?>
</th>
    <td><?php echo CHtml::encode($person->name_huy); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('name_tu')); ?>
</th>
    <td><?php echo CHtml::encode($person->name_tu); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('name_thuong')); ?>
</th>
    <td><?php echo CHtml::encode($person->name_thuong); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('conthumay')); ?>
</th>
    <td><?php echo CHtml::encode($person->conthumay); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('dob')); ?>
</th>
    <td><?php echo CHtml::encode($person->dob); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('dod')); ?>
</th>
    <td><?php echo CHtml::encode($person->dod); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('wod')); ?>
</th>
    <td><?php echo CHtml::encode($person->wod); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('huong_tho')); ?>
</th>
    <td><?php echo CHtml::encode($person->huong_tho); ?>
</td>
</tr>
<tr>
	<th class="label"><?php echo CHtml::encode($person->getAttributeLabel('detail')); ?>
</th>
    <td><?php echo CHtml::encode($person->detail); ?>
</td>
</tr>
</table>
