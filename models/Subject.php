<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "subject".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $created_by
 * @property string $updated_at
 * @property string $updated_by
 * @property string $class
 * @property integer $theme_id
 *
 * @property Theme $theme
 */
class Subject extends \yii\db\ActiveRecord
{

    /**
     *  @return app\models\Subject
     */
    public static function findModel($subjectId)
    {
        return static::find()->where("id=$subjectId")->one();
    }

	public static function getSubjectName($subjectId)
	{
		$subjModel = static::findModel($subjectId);
		return $subjModel->getTheme()->one()->title;// . ', ' . $subjModel->class;
	}

    /**
     *  @return app\utils\ThemeTreeElement
     */
    public static function getThemeTree($subjectId, $userId)
    {
        $themeModel = static::findModel($subjectId)->getTheme()->one();
        \Yii::trace($themeModel->title, 'info');
        return new \app\utils\ThemeTreeElement($themeModel, $userId);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
       return [
            [['created_at', 'created_by', 'updated_by', 'class', 'theme_id', 'domain_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by', 'theme_id', 'domain_id'], 'integer'],
            [['class'], 'string', 'max' => 255],
            [['domain_id'], 'exist', 'skipOnError' => true, 'targetClass' => Domain::className(), 'targetAttribute' => ['domain_id' => 'id']],
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
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'class' => 'Class',
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
