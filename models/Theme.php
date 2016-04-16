<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "theme".
 *
 * @property integer $id
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 * @property string $title
 * @property string $description
 * @property integer $parent
 *
 * @property Subject[] $subjects
 * @property Task[] $tasks
 */
class Theme extends \yii\db\ActiveRecord
{

	public static function getThemeTitle($themeId)
	{
		return static::find()->where("id=$themeId")->one()->title;
	}

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'theme';
    }

    public function behaviors()
    {
        return [
            'timestamp' => 
                    [       
                     'class' => \yii\behaviors\TimestampBehavior::className(),
                     'attributes' => [
                         \yii\db\BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                         \yii\db\BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                                     ],
                     'value' => new \yii\db\Expression('NOW()'),
                    ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
            [[/*'created_at',*/ 'created_by', 'updated_by'], 'required'],
           // [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'parent', 'complexity'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'title' => 'Название темы',
            'description' => 'Описание темы',
            'parent' => 'Parent',
			'complexity' => 'Сложность темы',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubjects()
    {
        return $this->hasMany(Subject::className(), ['theme_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['theme_id' => 'id']);
    }

	/**
     * @return \yii\db\ActiveQuery
     */
    public function getSubtheme()
    {
        return static::find()->where(['parent'=>$this->id]);
    }

	 /**
     * @return Theme - record for parent theme or null for subject theme
     */	
	public function getParent()
	{
		if ($this->parent == null)
			return null;
		return static::findOne($this->parent);
	}

	public function canBeDeleted()
	{
		return $this->getTasks()->count() == 0 && $this->getSubtheme()->count() == 0;
	}

    public static function makeEmptyTheme($userId)
    {
        $thModel = new Theme();
        //$thModel->parent = null;
        $thModel->created_by = $thModel->updated_by = $userId;
        //$thModel->description = 'Empty';
        //$thModel->title = 'Empty';
        if (!$thModel->save())
		{
			\Yii::trace("Save KIM theme", print_r($thModel->errors, true));
            throw new \yii\base\UserException("Cannot save KIM theme");
		}
        return $thModel;
    }
}
