<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "taskresult".
 *
 * @property integer $id
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 * @property integer $task_id
 * @property string $score
 * @property integer $num_tries
 *
 * @property Task $task
 */
class Taskresult extends \yii\db\ActiveRecord
{

    function __construct()
    {
        parent::__construct();
        $num_tries = 0;
        $score = null;
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
    public static function tableName()
    {
        return 'taskresult';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[/*'created_at',*/ 'user_id', 'task_id'], 'required'],
           // [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'task_id', 'num_tries'], 'integer'],
            [['score'], 'string', 'max' => 255],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'task_id' => 'Task ID',
            'score' => 'Score',
            'num_tries' => 'Num Tries',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \app\models\Taskresult
     */
    public static function getModelByTask($taskId, $userId)
    {
        $model = static::find()->where(['task_id' => $taskId])->andWhere(['user_id' => $userId])->one();
        if (!$model)
        {
            $model = new Taskresult();
            $model->task_id = $taskId;
            $model->user_id = $userId;
        }
        return $model;
    }
}
