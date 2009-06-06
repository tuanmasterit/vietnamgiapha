<?php $this->widget('CFlexWidget',array(
	'baseUrl'=>Yii::app()->baseUrl.'/tools/bin',
	'name'=>'vngpEditor',
	'width'=>'960',
	'height'=>'600',
	'align'=>'left',
	'flashVars'=>array(
		'xmlTree'=>$this->createUrl('tree/xml'),
		'root_id'=>'0',
	))); ?>