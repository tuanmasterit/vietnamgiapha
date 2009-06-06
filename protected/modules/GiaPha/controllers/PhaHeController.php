<?php

class PhaHeController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_phahe;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'list' and 'show' actions
				'actions'=>array('list','show'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Shows a particular phahe.
	 */
	public function actionShow()
	{
		$this->render('show',array('phahe'=>$this->loadPhaHe()));
	}

	/**
	 * Creates a new phahe.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$phahe=new PhaHe;
		if(isset($_POST['PhaHe']))
		{
			$phahe->attributes=$_POST['PhaHe'];
			if($phahe->save())
				$this->redirect(array('show','id'=>$phahe->id));
		}
		$this->render('create',array('phahe'=>$phahe));
	}

	/**
	 * Updates a particular phahe.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$phahe=$this->loadPhaHe();
		if(isset($_POST['PhaHe']))
		{
			$phahe->attributes=$_POST['PhaHe'];
			if($phahe->save())
				$this->redirect(array('show','id'=>$phahe->id));
		}
		$this->render('update',array('phahe'=>$phahe));
	}

	/**
	 * Deletes a particular phahe.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadPhaHe()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(500,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all phahes.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(PhaHe::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$phaheList=PhaHe::model()->findAll($criteria);

		$this->render('list',array(
			'phaheList'=>$phaheList,
			'pages'=>$pages,
		));
	}

	/**
	 * Manages all phahes.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(PhaHe::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('PhaHe');
		$sort->applyOrder($criteria);

		$phaheList=PhaHe::model()->findAll($criteria);

		$this->render('admin',array(
			'phaheList'=>$phaheList,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadPhaHe($id=null)
	{
		if($this->_phahe===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_phahe=PhaHe::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_phahe===null)
				throw new CHttpException(500,'The requested phahe does not exist.');
		}
		return $this->_phahe;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadPhaHe($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
