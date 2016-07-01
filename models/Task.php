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
 * @property string $updated_at
 * @property string $updated_by
 * @property string $max_score
 * @property string $struct_type
 * @property integer $theme_id
 *
 * @property Theme $theme
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
		$fileName = Yii::getAlias($this->taskType->getPrototypeFilename());
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
      	return [
            //[['created_at', 'updated_at'], 'safe'],
            [['created_by', 'content', 'updated_by', 'max_score', 'struct_type', 'theme_id'], 'required'],
            [['created_by', 'updated_by', 'theme_id'], 'integer'],
            [['content'], 'string'],
            [['max_score', 'struct_type'], 'string', 'max' => 255],
            [['theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Theme::className(), 'targetAttribute' => ['theme_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
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

    public static function copyTask($taskId, $themeId)
    {
    	$model = self::findModel($taskId);
    	$newModel = new Task();
    	$newModel->created_by = $newModel->updated_by = $model->created_by;
    	$newModel->max_score = $model->max_score;
    	$newModel->content = $model->content;
    	$newModel->struct_type = $model->struct_type;
    	$newModel->theme_id = $themeId;
    	$newModel->created_at = $model->created_at;	
    	$newModel->loadType();
    	//$$newModel->updated_at = $model->updated_at;
    	if (!$newModel->save())
    		throw new \app\utils\TaskException("Cannot copy task");
    }
}
