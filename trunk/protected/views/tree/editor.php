<?php $this->widget('CFlexWidget',array(
	'baseUrl'=>Yii::app()->baseUrl.'/tools/bin',
	'name'=>'vngpEditor',
	'width'=>'960',
	'height'=>'600',
	'align'=>'left',
	'flashVars'=>array(
		'xmlTreeUrl'=>$this->createUrl('tree/xml'),
		'saveTreeUrl'=>$this->createUrl('tree/saveXML'),
		'root_id'=>'0',
	))); ?>