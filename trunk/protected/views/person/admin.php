<h2>Managing Person</h2>

<div class="actionBar">
[<?php echo CHtml::link('Person List',array('list')); ?>]
[<?php echo CHtml::link('New Person',array('create')); ?>]
</div>

<table class="dataGrid">
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th><?php echo $sort->link('phahe_id'); ?></th>
    <th><?php echo $sort->link('family_id'); ?></th>
    <th><?php echo $sort->link('pid'); ?></th>
    <th><?php echo $sort->link('pic'); ?></th>
    <th><?php echo $sort->link('sts'); ?></th>
    <th><?php echo $sort->link('gender'); ?></th>
    <th><?php echo $sort->link('name_thuy'); ?></th>
    <th><?php echo $sort->link('name_huy'); ?></th>
    <th><?php echo $sort->link('name_tu'); ?></th>
    <th><?php echo $sort->link('name_thuong'); ?></th>
    <th><?php echo $sort->link('conthumay'); ?></th>
    <th><?php echo $sort->link('dob'); ?></th>
    <th><?php echo $sort->link('dod'); ?></th>
    <th><?php echo $sort->link('wod'); ?></th>
    <th><?php echo $sort->link('huong_tho'); ?></th>
	<th>Actions</th>
  </tr>
<?php foreach($personList as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>
    <td><?php echo CHtml::encode($model->phahe_id); ?></td>
    <td><?php echo CHtml::encode($model->family_id); ?></td>
    <td><?php echo CHtml::encode($model->pid); ?></td>
    <td><?php echo CHtml::encode($model->pic); ?></td>
    <td><?php echo CHtml::encode($model->sts); ?></td>
    <td><?php echo CHtml::encode($model->gender); ?></td>
    <td><?php echo CHtml::encode($model->name_thuy); ?></td>
    <td><?php echo CHtml::encode($model->name_huy); ?></td>
    <td><?php echo CHtml::encode($model->name_tu); ?></td>
    <td><?php echo CHtml::encode($model->name_thuong); ?></td>
    <td><?php echo CHtml::encode($model->conthumay); ?></td>
    <td><?php echo CHtml::encode($model->dob); ?></td>
    <td><?php echo CHtml::encode($model->dod); ?></td>
    <td><?php echo CHtml::encode($model->wod); ?></td>
    <td><?php echo CHtml::encode($model->huong_tho); ?></td>
    <td>
      <?php echo CHtml::link('Update',array('update','id'=>$model->id)); ?>
      <?php echo CHtml::linkButton('Delete',array(
      	  'submit'=>'',
      	  'params'=>array('command'=>'delete','id'=>$model->id),
      	  'confirm'=>"Are you sure to delete #{$model->id}?")); ?>
	</td>
  </tr>
<?php endforeach; ?>
</table>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>