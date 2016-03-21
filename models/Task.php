<?php

namespace app\models;

use Yii;
use \app\utils;
use \yii\db\BaseActiveRecord;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $content
 */
class Task extends \yii\db\ActiveRecord
{
	const EDIT_SUBMIT_BUTTON = 'submitEditB';
	const SAVE_SUBMIT_BUTTON = 'submitSaveB';

	public $taskType = NULL;

	/**
     * @return \app\models\Task
     */
	public static function findModel($id)
	{
		return static::find()->where(['id'=>$id])->one();
	}
	
	/**
     * @return \yii\db\ActiveQuery
     */
	public static function getTasksForTheme($theme_id)
	{
		return static::find()->where(['theme_id'=>$theme_id]);
	}
	
	private function loadType()
	{
		$this->taskType = \app\utils\TaskType::loadTaskType($this->struct_type);
		//$this->attachBehavior('tasktype', $this->taskType->makeBehavior());
		//$this->taskType->setModelAttributes($this);	
	}

	public function loadFromPrototype()
	{
		$fileName = Yii::$app->basePath .'/views/task/'. $this->taskType->getPrototypeFilename();
		$this->content = file_get_contents($fileName);
		$this->updateFromContent();
	}

	public function prepareForSolving()
	{
		$this->attachBehavior('tasktype', $this->taskType->makeBehavior());
		$this->updateFromContent();
	}

	public function setType($type)
	{
		$this->struct_type = $type;
		$this->loadType();
	}

	public function afterFind()
	{
		parent::afterFind();
		$this->loadType();
		//if ($this->taskType)
		//	$this->taskType->setModelAttributes($this);
	}
	
	private function updateFromContent()
	{
		$this->taskType->traverse(['model'=>$this, 'action'=>\app\utils\TaskType::PARSE_ACTION]);
		return true;
	}	

	public function checkAnswer($postResponse)
	{
		return $this->taskType->checkAnswer($this, $postResponse);
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }


	public function behaviors()
	{
		return [
			'timestamp' => 
					[		
                     'class' => \yii\behaviors\TimestampBehavior::className(),
                     'attributes' => [
                         BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                         BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                   					 ],
					 'value' => new \yii\db\Expression('NOW()'),
					],
		];
	}

	public function beforeValidate()
	{
		\Yii::trace("before validating", "In id=" . $this->id);
		if (!$this->taskType || !$this->updateFromContent())
			return false;
		return parent::beforeValidate();
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return //array_merge(
		  [
           // [['created_at'], 'safe'],
           // [['updated_at'], 'safe'],
			[['taskType'], 'safe'],
            [['created_by', 'content', 'updated_by', 'max_score', 'struct_type', 'theme_id'], 'required'],
            [['content'], 'string'],
            [['theme_id'], 'integer'],
            [['created_by'], 'string', 'max' => 255],
 			[['updated_by'], 'string', 'max' => 255], 
			[['max_score'], 'string', 'max' => 255],
			[['struct_type'], 'string', 'max' => 32],
         ]
		//, $this->getBehaviors()['tasktype']->getRules())
		;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return //array_merge( 
			[
            'id' => 'ID',
            'created_at' => 'Создано',
            'created_by' => 'Автор',
            'updated_at' => 'Изменено',
            'updated_by' => 'Редактор',
     		'content' => '',
			'max_score' => 'Балл',
        	]
			//, $this->taskType ? $this->getBehaviors()['tasktype']->getAttributeLabels() : [] )
			;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }
}
