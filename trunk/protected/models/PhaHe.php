<?php

class PhaHe extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'PhaHe';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name','length','max'=>150),
			array('created_name','length','max'=>150),
			array('created_by_id', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'Id',
			'name'=>'Name',
			'content'=>'Content',
			'created_on'=>'Created On',
			'updated_on'=>'Updated On',
			'created_by_id'=>'Created By ',
			'created_name'=>'Created Name',
		);
	}
}