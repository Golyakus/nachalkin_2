<?php

namespace app\controllers;

use Yii;
use app\models\Kim;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KimController implements the CRUD actions for Kim model.
 */
class KimController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
			'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
					[
                        'actions' => ['index', 'view', 'description', 'create', 'update', 'delete', 'deltask', 'delt', 'tasklist', 'addtask', 'addt'],
                        'allow' => true,
                        'roles' => ['teacher'],	
					]
                ],
            ],			
        ];
    }

    /**
     * Lists all Kim models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Kim::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kim model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kim model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($subjectId)
    {
        $model = new Kim();
		$model->initialPopulate($subjectId);       
        $post = Yii::$app->request->post();
        if ($model->load($post)) 
        {
            $themeModel = \app\models\Theme::makeEmptyTheme(\Yii::$app->user->id);
            $model->theme_id = $themeModel->id;
            return $this->processPostKimRequest($post, $model);
        } 
        else 
        {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    private function processPostKimRequest($post, $model)
    {
        // TODO:::
        $subjectId = 1;

        if (!$model->save())
		{
            throw new \yii\base\UserException("Cannot save loaded KIM");
		}
        if (isset($post['submitb']))
        {
            if ($post['submitb'] == "addtask")
                return $this->redirect(['addtask', 'id' => $model->id]);
            else if ($post['submitb'] == "savekim")
                return $this->redirect('/site/index');
            print_r($post);
        }
        else if (isset($post['edittask']))
        {
            //return $this->redirect(['edittask', 'id' => $post['edittask']]);
            return $this->redirect(['/task/updatekim/' . $post['edittask'] . '/' . $subjectId]);
        }
        else // should not be here!!!! check and throw Exception....
            return $this->redirect(['view', 'id' => $model->id]);
    }

	private static function checkAccess($model)
	{
		if (!\Yii::$app->user->can('admin') && $model->updated_by != \Yii::$app->user->id && $model->created_by != \Yii::$app->user->id )
			throw new \yii\web\ForbiddenHttpException('Вы не авторизованы для выполнения этого действия');
	}

    public function actionAddtask($id)
    {
        
        $post = Yii::$app->request->post();
        if (isset($post[\app\models\Kim::KIM_ADDTASK_BUTTON_NAME]))
        {
            $this->redirect("update?id=" . $id);
        } else {
            $model = $this->findModel($id);
			self::checkAccess($model);
            return $this->render('addtask', ['model' => $model]);        
        }            
    }

    /*****************AJAX handlers **********************************************/
    /**
     * returns html snippet with theme description for AJAX GET request
    */
    public function actionDescription($id)
    {
        $model = \app\models\Theme::findOne($id);
        return $this->renderPartial('_description', ['model' => $model]);
    }

    private static function makeBoolArray($a)
    {
        $retval = [];
        foreach ($a as $el)
        {
            $retval[(string)($el['id'])] = true;
        }
        return $retval;
    }

	

    /**
     * returns html snippet with list of tasks for KIM or theme for AJAX GET request
    */ 
    public function actionTasklist($id, $kimThemeId)
    {
        $model = \app\models\Theme::findOne($id);
        $dataProvider = new \yii\data\ActiveDataProvider(['query' => \app\models\Task::find()->where (['theme_id'=>$model->id])]);
        $kimTasks = self::makeBoolArray(\app\models\Task::find()->where (['theme_id'=>$kimThemeId])->select('id')->asArray()->all());
        //print_r($kimTasks);
        return $this->renderPartial('_tasklist', compact('model','dataProvider', 'kimThemeId', 'kimTasks'));
    }

    public function actionAddt($id, $themeId)
    {
        \app\models\Task::copyTask($id, $themeId);
        return $this->renderPartial('_addedbutton',['id' => $id]);
    }

    /*****************End of AJAX handlers **********************************************/
    

    /**
     * Updates an existing Kim model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		self::checkAccess($model);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            return $this->processPostKimRequest($post, $model);
        } else {
            $dataProvider = new \yii\data\ActiveDataProvider(
                    ['query' => \app\models\Task::find()->where (['theme_id'=>$model->theme_id])]
            );           
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Deletes an existing Kim model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
		$model = $this->findModel($id);
		self::checkAccess($model);
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Kim model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Kim the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kim::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDeltask($id)
    {
        $task = \app\models\Task::findOne($id);
        $model = \app\models\Kim::find()->where(['theme_id'=>$task->theme_id])->one();
        \Yii::trace($task->id, $task->theme_id);
        $task->delete();
        
        return $this->redirect(['update?id='.$model->id]);
    }
}
