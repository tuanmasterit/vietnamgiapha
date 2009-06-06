<h2>Tree List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New Tree',array('create')); ?>]
[<?php echo CHtml::link('Manage Tree',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($treeList as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('lft')); ?>:
<?php echo CHtml::encode($model->lft); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('rgt')); ?>:
<?php echo CHtml::encode($model->rgt); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('level')); ?>:
<?php echo CHtml::encode($model->level); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name')); ?>:
<?php echo CHtml::encode($model->name); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>