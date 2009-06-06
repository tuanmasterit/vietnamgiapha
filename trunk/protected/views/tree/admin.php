<h2>Managing Tree</h2>

<div class="actionBar">
[<?php echo CHtml::link('Tree List',array('list')); ?>]
[<?php echo CHtml::link('New Tree',array('create')); ?>]
</div>

<table class="dataGrid">
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th><?php echo $sort->link('lft'); ?></th>
    <th><?php echo $sort->link('rgt'); ?></th>
    <th><?php echo $sort->link('level'); ?></th>
    <th><?php echo $sort->link('name'); ?></th>
	<th>Actions</th>
  </tr>
<?php foreach($treeList as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>
    <td><?php echo CHtml::encode($model->lft); ?></td>
    <td><?php echo CHtml::encode($model->rgt); ?></td>
    <td><?php echo CHtml::encode($model->level); ?></td>
    <td><?php echo CHtml::encode($model->name); ?></td>
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