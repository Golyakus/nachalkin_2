<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "theme".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_by', 'title', 'description'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['parent'], 'integer'],
            [['created_by', 'updated_by', 'title'], 'string', 'max' => 255],
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
}
