<?php

class TreeController extends CController
{
	const PAGE_SIZE=10;

	/**
	 * @var string specifies the default action to be 'list'.
	 */
	public $defaultAction='list';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_tree;

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
				'actions'=>array('xml', 'list','show', 'saveXML'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('editor', 'create','update'),
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
	 * Shows a particular tree.
	 */
	public function actionShow()
	{
		$this->render('show',array('tree'=>$this->loadTree()));
	}

	/**
	 * Creates a new tree.
	 * If creation is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionCreate()
	{
		$tree=new Tree;
		if(isset($_POST['Tree']))
		{
			$tree->attributes=$_POST['Tree'];
			if($tree->save())
				$this->redirect(array('show','id'=>$tree->id));
		}
		$this->render('create',array('tree'=>$tree));
	}

	/**
	 * Updates a particular tree.
	 * If update is successful, the browser will be redirected to the 'show' page.
	 */
	public function actionUpdate()
	{
		$tree=$this->loadTree();
		if(isset($_POST['Tree']))
		{
			$tree->attributes=$_POST['Tree'];
			if($tree->save())
				$this->redirect(array('show','id'=>$tree->id));
		}
		$this->render('update',array('tree'=>$tree));
	}

	/**
	 * Deletes a particular tree.
	 * If deletion is successful, the browser will be redirected to the 'list' page.
	 */
	public function actionDelete()
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadTree()->delete();
			$this->redirect(array('list'));
		}
		else
			throw new CHttpException(500,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all trees.
	 */
	public function actionList()
	{
		$criteria=new CDbCriteria;

		$pages=new CPagination(Tree::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$treeList=Tree::model()->findAll($criteria);

		$this->render('list',array(
			'treeList'=>$treeList,
			'pages'=>$pages,
		));
	}
	
	public function actionXML() {
		header('Content-Type: text/xml');
		echo Tree::model()->printTree();
		exit();
	}
	
	public function actionEditor() {
		$this->render('editor');
	}
	
	public function actionSaveXML() {
		header('Content-Type: text/xml');
		$xml = $_POST['giapha'];
		Tree::model()->xml2tree($xml);
		exit();
	}

	/**
	 * Manages all trees.
	 */
	public function actionAdmin()
	{
		$this->processAdminCommand();

		$criteria=new CDbCriteria;

		$pages=new CPagination(Tree::model()->count($criteria));
		$pages->pageSize=self::PAGE_SIZE;
		$pages->applyLimit($criteria);

		$sort=new CSort('Tree');
		$sort->applyOrder($criteria);

		$treeList=Tree::model()->findAll($criteria);

		$this->render('admin',array(
			'treeList'=>$treeList,
			'pages'=>$pages,
			'sort'=>$sort,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the primary key value. Defaults to null, meaning using the 'id' GET variable
	 */
	public function loadTree($id=null)
	{
		if($this->_tree===null)
		{
			if($id!==null || isset($_GET['id']))
				$this->_tree=Tree::model()->findbyPk($id!==null ? $id : $_GET['id']);
			if($this->_tree===null)
				throw new CHttpException(500,'The requested tree does not exist.');
		}
		return $this->_tree;
	}

	/**
	 * Executes any command triggered on the admin page.
	 */
	protected function processAdminCommand()
	{
		if(isset($_POST['command'], $_POST['id']) && $_POST['command']==='delete')
		{
			$this->loadTree($_POST['id'])->delete();
			// reload the current page to avoid duplicated delete actions
			$this->refresh();
		}
	}
}
