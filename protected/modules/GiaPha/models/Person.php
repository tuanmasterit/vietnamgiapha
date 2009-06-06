<?php

class Person extends CActiveRecord
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
		return 'Person';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('pid','length','max'=>16),
			array('pic','length','max'=>64),
			array('gender','length','max'=>1),
			array('name_thuy','length','max'=>96),
			array('name_huy','length','max'=>96),
			array('name_tu','length','max'=>32),
			array('name_thuong','length','max'=>32),
			array('dob','length','max'=>32),
			array('dod','length','max'=>32),
			array('wod','length','max'=>255),
			array('phahe_id, parent_id', 'required'),
			array('phahe_id, parent_id, family_id, sts, conthumay, huong_tho', 'numerical', 'integerOnly'=>true),
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
			'phahe_id'=>'Phahe ',
			'parent_id'=>'Parent ',
			'family_id'=>'Family ',
			'pid'=>'P',
			'pic'=>'Pic',
			'sts'=>'Sts',
			'gender'=>'Gender',
			'name_thuy'=>'Name Thuy',
			'name_huy'=>'Name Huy',
			'name_tu'=>'Name Tu',
			'name_thuong'=>'Name Thuong',
			'conthumay'=>'Conthumay',
			'dob'=>'Dob',
			'dod'=>'Dod',
			'wod'=>'Wod',
			'huong_tho'=>'Huong Tho',
			'detail'=>'Detail',
		);
	}
	
	public function beforeSave() {
		if ($this->isNewRecord) {
			$sql = "SELECT id FROM family WHERE father_id={$this->parent_id} AND phahe_id={$this->phahe_id}";
			$familyId = $this->dbConnection->createCommand($sql)->queryScalar();
			if ($familyId) {
				$this->family_id = $familyId;  
			} else {
				//$father = Person::findByPk($this->parent_id);
				$insert = "INSERT INTO family(phahe_id, father_id, LEVEL, sort_order) VALUES ({$this->phahe_id}, {$this->parent_id}, 0, 0)";
				$this->dbConnection->createCommand($insert)->execute();
				$this->family_id = $this->dbConnection->getLastInsertID(); 
			}
			return true;
		}
		return false;
	}
}