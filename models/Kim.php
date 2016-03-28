<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kim".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $theme_id
 * @property integer $subject_id
 * @property integer $status
 * @property integer $solvetime
 *
 * @property Subject $subject
 * @property Theme $theme
 */
class Kim extends \yii\db\ActiveRecord
{
    const KIM_STATUS_DRAFT = 0;
    const KIM_STATUS_READY = 1;
    const KIM_STATUS_PUBLISHED = 2;

    const KIM_ADDTASK_BUTTON_NAME = 'addtask';

    public static $status_strings = [
        self::KIM_STATUS_DRAFT =>'Черновик', 
        self::KIM_STATUS_READY =>'Готов к выдаче', 
        self::KIM_STATUS_PUBLISHED =>'Выдан детям',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'kim';
    }

    /** properties from Theme record
    */
    public $title;
    public  $description;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'created_by', 'updated_by', 'theme_id', 'subject_id', 'solvetime'], 'required'],
            [['title', 'description'], 'safe'],
            [['created_by', 'updated_by', 'theme_id', 'subject_id', 'status', 'solvetime'], 'integer'],
            [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => Subject::className(), 'targetAttribute' => ['subject_id' => 'id']],
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
            'updated_at' => 'Дата изменения',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'theme_id' => '',
            'subject_id' => 'Subject ID',
            'status' => 'Статус',
            'title' => 'Название',
            'description' => 'Описание задания',
            'solvetime' => 'Время на выполнение задания',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getSubject()
    {
        return $this->hasOne(Subject::className(), ['id' => 'subject_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTheme()
    {
        return $this->hasOne(Theme::className(), ['id' => 'theme_id']);
    }

    private function updateTheme($themeModel)
    {
        if($themeModel->title != $this->title || $themeModel->description != $this->description)
        {
            $themeModel->title = $this->title;
            $themeModel->description = $this->description;
            $themeModel->updated_by = (string)($this->updated_by);            
        }
    }

    private function updateFromTheme($themeModel)
    {
        if($themeModel->title != $this->title || $themeModel->description != $this->description)
        {
            $this->title = $themeModel->title;
            $this->description = $themeModel->description;          
        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $themeModel = $this->getTheme()->one();
        $this->updateTheme($themeModel);      
        if(!$themeModel->save())
            throw new \yii\base\UserException(" Error updating theme while saving KIM " . $this->id);
    }

    public function afterFind()
    {
        parent::afterFind();
        $themeModel = $this->getTheme()->one();
        $this->updateFromTheme($themeModel);
    }

    public function getName()
    {
        return $this->title;
    }

    public function taskNumber()
    {
        \Yii::trace($this->theme_id, $this->id);
        return Task::find()->andWhere(['theme_id' => $this->theme_id])->count();
    }

}
