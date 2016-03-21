<?php

namespace app\models;

use Yii;

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
class TaskRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'content', 'updated_at', 'updated_by', 'max_score', 'struct_type', 'theme_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['content'], 'string'],
            [['theme_id'], 'integer'],
            [['created_by', 'updated_by', 'max_score', 'struct_type'], 'string', 'max' => 255],
            [['theme_id'], 'exist', 'skipOnError' => true, 'targetClass' => Theme::className(), 'targetAttribute' => ['theme_id' => 'id']],
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
            'content' => 'Content',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'max_score' => 'Max Score',
            'struct_type' => 'Struct Type',
            'theme_id' => 'Theme ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }
}
