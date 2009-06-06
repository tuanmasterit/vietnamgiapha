<h2>PhaHe List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New PhaHe',array('create')); ?>]
[<?php echo CHtml::link('Manage PhaHe',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($phaheList as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name')); ?>:
<?php echo CHtml::encode($model->name); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('content')); ?>:
<?php echo CHtml::encode($model->content); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('created_on')); ?>:
<?php echo CHtml::encode($model->created_on); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('updated_on')); ?>:
<?php echo CHtml::encode($model->updated_on); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('created_by_id')); ?>:
<?php echo CHtml::encode($model->created_by_id); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('created_name')); ?>:
<?php echo CHtml::encode($model->created_name); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>