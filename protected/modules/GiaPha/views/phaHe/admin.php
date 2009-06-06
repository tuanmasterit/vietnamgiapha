<h2>Managing PhaHe</h2>

<div class="actionBar">
[<?php echo CHtml::link('PhaHe List',array('list')); ?>]
[<?php echo CHtml::link('New PhaHe',array('create')); ?>]
</div>

<table class="dataGrid">
  <tr>
    <th><?php echo $sort->link('id'); ?></th>
    <th><?php echo $sort->link('name'); ?></th>
    <th><?php echo $sort->link('created_on'); ?></th>
    <th><?php echo $sort->link('updated_on'); ?></th>
    <th><?php echo $sort->link('created_by_id'); ?></th>
    <th><?php echo $sort->link('created_name'); ?></th>
	<th>Actions</th>
  </tr>
<?php foreach($phaheList as $n=>$model): ?>
  <tr class="<?php echo $n%2?'even':'odd';?>">
    <td><?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?></td>
    <td><?php echo CHtml::encode($model->name); ?></td>
    <td><?php echo CHtml::encode($model->created_on); ?></td>
    <td><?php echo CHtml::encode($model->updated_on); ?></td>
    <td><?php echo CHtml::encode($model->created_by_id); ?></td>
    <td><?php echo CHtml::encode($model->created_name); ?></td>
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