<?php

namespace app\controllers;

use Yii;
use app\models\Theme;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ThemeController implements the CRUD actions for Theme model.
 */
class ThemeController extends Controller
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
                        'actions' => ['description', 'list', 'create', 'update', 'delete', 'index', 'view'],
                        'allow' => true,
                        'roles' => ['admin-teacher'],	
					],
					[
						'actions' => ['view'],
						'allow' => true,
						'roles'=> ['task-editor'],
					]
                ],
            ],			
        ];
    }

    /**
     * returns html snippet with theme description for AJAX GET request
    */
    public function actionDescription($id, $subjectId)
    {
        $model = $this->findModel($id);
        return $this->renderPartial('_description', compact('model','subjectId'));
    }

    /**
     * Show all themes for the user(pupil)
    */

    public function actionList($subjectId)
    {
        $themeTree = \app\models\Subject::getThemeTree($subjectId, null);
        //$themeTree->print_r();
        //$themeId = \app\models\Subject::findModel($subjectId)->id;
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels'=>$themeTree->children,
            'pagination' => false,
        ]);  
        return $this->render('list', compact('themeId', 'subjectId', 'dataProvider'));     
    }

    /**
     * Lists all Theme models.
     * @return mixed
     */
    public function actionIndex($subjectId, $themeId)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Theme::find()->where("parent=$themeId"),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
			'subjectId' => $subjectId,
			'model'=> $this->findModel($themeId)
        ]);
    }

    /**
     * Displays a single Theme model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $subjectId)
    {
		$model = $this->findModel($id);
		$dataProvider = new ActiveDataProvider([
            'query' => $model->getTasks(),
		]);
        return $this->render('view', compact('model', 'subjectId', 'dataProvider'));
    }

    /**
     * Creates a new Theme model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($subjectId, $parentId = null)
    {
        $model = new Theme();
		$model->parent = $parentId;
		$model->created_by = $model->updated_by = \Yii::$app->user->id;
		// TODO: add TimestampBehavior!!!
		date_default_timezone_set('Europe/Moscow');
		$curTime = new \DateTime();
		$model->created_at = $curTime->format('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'subjectId'=>$subjectId]);
        } else {
            return $this->render('create', [
                'model' => $model,
				'subjectId' => $subjectId
            ]);
        }
    }

    /**
     * Updates an existing Theme model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $subjectId)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'subjectId'=>$subjectId]);
        } else {
            return $this->render('update', [
                'model' => $model,
				'subjectId'=>$subjectId
            ]);
        }
    }

    /**
     * Deletes an existing Theme model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $subjectId)
    {
		$model = $this->findModel($id);
		$parent = $model->parent;        
		$model->delete();

        return $this->redirect(['index', 'subjectId' => $subjectId, 'themeId'=>$parent ]);
    }

    /**
     * Finds the Theme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Theme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Theme::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
