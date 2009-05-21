<h2>Person List</h2>

<div class="actionBar">
[<?php echo CHtml::link('New Person',array('create')); ?>]
[<?php echo CHtml::link('Manage Person',array('admin')); ?>]
</div>

<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>

<?php foreach($personList as $n=>$model): ?>
<div class="item">
<?php echo CHtml::encode($model->getAttributeLabel('id')); ?>:
<?php echo CHtml::link($model->id,array('show','id'=>$model->id)); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('phahe_id')); ?>:
<?php echo CHtml::encode($model->phahe_id); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('family_id')); ?>:
<?php echo CHtml::encode($model->family_id); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('pid')); ?>:
<?php echo CHtml::encode($model->pid); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('pic')); ?>:
<?php echo CHtml::encode($model->pic); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('sts')); ?>:
<?php echo CHtml::encode($model->sts); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('gender')); ?>:
<?php echo CHtml::encode($model->gender); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name_thuy')); ?>:
<?php echo CHtml::encode($model->name_thuy); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name_huy')); ?>:
<?php echo CHtml::encode($model->name_huy); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name_tu')); ?>:
<?php echo CHtml::encode($model->name_tu); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('name_thuong')); ?>:
<?php echo CHtml::encode($model->name_thuong); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('conthumay')); ?>:
<?php echo CHtml::encode($model->conthumay); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('dob')); ?>:
<?php echo CHtml::encode($model->dob); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('dod')); ?>:
<?php echo CHtml::encode($model->dod); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('wod')); ?>:
<?php echo CHtml::encode($model->wod); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('huong_tho')); ?>:
<?php echo CHtml::encode($model->huong_tho); ?>
<br/>
<?php echo CHtml::encode($model->getAttributeLabel('detail')); ?>:
<?php echo CHtml::encode($model->detail); ?>
<br/>

</div>
<?php endforeach; ?>
<br/>
<?php $this->widget('CLinkPager',array('pages'=>$pages)); ?>