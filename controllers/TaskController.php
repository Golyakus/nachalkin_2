<?php

namespace app\controllers;

use Yii;
use app\models\Task;
use app\models\TaskSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TaskController implements the CRUD actions for Task model.
 */
class TaskController extends Controller
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
        ];
    }

    /**
     * Lists all Task models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaskSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            //'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Task model.
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
     * Creates a new Task model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type,$subjectId,$themeId)
    {
        $model = new Task();
		$model->created_by = $model->updated_by = 'igor';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Task model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id, $subjectId)
    {
        $model = $this->findModel($id);
		$themeId = $model->theme_id;
		$theme = static::getThemeParams($subjectId, $themeId); 
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/theme/view', 'id'=>$themeId,'subjectId'=>$subjectId]);
        } else {
			$params['invitation'] = 'Редактирование упражнения' . " в теме «" . $theme['subtheme'] . "»";
			$params['action'] = \app\utils\TaskType::RENDER_EDIT_ACTION;		
            return $this->render('update', compact('model', 'theme', 'params'));
        }
    }

    /**
     * Deletes an existing Task model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Task model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Task the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Task::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	private static function getThemeParams($subjectId, $themeId)
	{
		$subject = \app\models\Subject::getSubjectName($subjectId);
		$subtheme = \app\models\Theme::getThemeTitle($themeId);
		return compact('subject','subtheme','subjectId','themeId');
	}

    /**
     * Displays "Choose Task Type" page
     */
    public function actionChoosetype($subjectId, $themeId)
    {
        $tasktypes = \app\utils\TaskType::loadAllTypes();
		$theme = static::getThemeParams($subjectId, $themeId);
        return $this->render('choosetype', compact('tasktypes', 'theme'));
    }

	public function actionNewtask($type,$subjectId,$themeId)
	{
		$model = new Task();
		$model->setType($type);
		$model->loadFromPrototype();
		$model->created_by = $model->updated_by = 'igor';
		$model->theme_id = $themeId;
		$theme = static::getThemeParams($subjectId, $themeId); 
        if ($model->load(Yii::$app->request->post())) {
			$params['invitation'] = 'Просмотрите упражнение перед сохранением';			
			$params['action'] = \app\utils\TaskType::RENDER_VIEW_ACTION;
       	} else if (isset(\Yii::$app->request->post()[\app\models\Task::SAVE_SUBMIT_BUTTON])) {
			$model->created_by = $model->updated_by = 'igor';
			$model->content = \Yii::$app->request->post()[\app\models\Task::SAVE_SUBMIT_BUTTON];
			if ($model->save())
            	return $this->redirect(["task/choosetype/$subjectId/$themeId"]);
			else {
				print_r($model->getErrors());
				$params['invitation'] = 'Проверьте правильность заполнения условия';			
				$params['action'] = \app\utils\TaskType::RENDER_EDIT_ACTION;
			}
		} else if (isset(\Yii::$app->request->post()[\app\models\Task::EDIT_SUBMIT_BUTTON])) {
			$model->content = \Yii::$app->request->post()[\app\models\Task::EDIT_SUBMIT_BUTTON];
			$params['invitation'] = 'Добавление упражнения';
			$params['action'] = \app\utils\TaskType::RENDER_EDIT_ACTION;					
		} else {		
			$params['invitation'] = 'Добавление упражнения';
			$params['action'] = \app\utils\TaskType::RENDER_EDIT_ACTION;
        }
		return $this->render('newtask', compact('model', 'theme', 'params'));
	}

    public function actionUpload()
    {
        $response = \Yii::$app->response;
        $response->format =\yii\web\Response::FORMAT_JSON;

        if (!empty($_FILES['files'])) {
            $response->data = $this->processUploadedFile(0);
        }
        else {
            $response->data = ['error'=>'Ошибка загрузки файла на сервер'];
        }
        return $response;
    }

    private function processUploadedFile($index)
    {
        define ("UPLOAD_URL", '/uploads/');
        define("UPLOAD_DIR", \Yii::$app->basePath . '/web' . UPLOAD_URL);

        $myFile = $_FILES['files'];

        if ($myFile["error"][$index] !== UPLOAD_ERR_OK) {
            return ['error'=>'An error occurred'];
        }

        // ensure a safe filename
        $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"][$index]);

        // don't overwrite an existing file
        $i = 0;
        $parts = pathinfo($name);
        while (file_exists(UPLOAD_DIR . $name)) {
            $i++;
            $name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
        }

        // preserve file from temporary directory
        $success = move_uploaded_file($myFile["tmp_name"][$index], UPLOAD_DIR . $name);
        if (!$success) { 
            return ['error'=>'Ошибка при сохранении файла'];
        
        }

        // set proper permissions on the new file
        chmod(UPLOAD_DIR . $name, 0644);
        return 
            [
                'files'=>[
                    0=>[
                    'url'=>UPLOAD_URL . $name,
                    'name'=>$name,
                    'size'=>$myFile['size'][$index],
                    'type'=>$myFile['type'][$index],
                    'deleteUrl'=>UPLOAD_URL . $name,
                    'deleteType'=>'DELETE',
                    ],
                ]       
            ];
    }

    public function actionSolverand($themeId)
    {
    	// TODO:    	
    	// select * from Task where Task.theme_id = $themeId and Task.id NOT IN (select task_id from Taskresult where Taskresult.user_id = 102 and not Taskresult.score is null)
    	// now select only tasks
    	$q = \app\models\Task::getTasksForTheme($themeId)->select('id')->asArray();

    	if (($n = $q->count()) == 0)
    	{
    		throw new \yii\base\UserException("В теме с ключом $themeId нет задач");
    	}
    	$number = mt_rand(0, $n-1);
    	$ids = $q->all();
    	$response = $this->solveTask($ids[$number]);
    	if (!$response) //TODO:: get the subject - cookie???
    		return $this->redirect('/theme/list?subjectId=1');
    	return $response;


    }

    public function actionSolve($id)
    {
    	$response = $this->solveTask($id);
    	if (!$response) //TODO:: get the subject - cookie???
    		return $this->redirect('/theme/list?subjectId=1');
    	return $response;
    }

    private function solveTask($id)
    {
    	$model = \app\models\Taskresult::getModelByTask($id, 102);
    	$taskModel = \app\models\Task::findModel($model->task_id);
        $taskModel->prepareForSolving();
        $score = $taskModel->checkAnswer(Yii::$app->request->post());
        \Yii::trace($score, 'score');
    	if ($score != "")
    	{
    		$model->num_tries++;
    		$model->score = $score;
    		if (!$model->save())
                \Yii::trace("Error saving task result....", 'info');
    		//return false;
return $this->render('solve', compact('model', 'taskModel'));
    	}
    	return $this->render('solve', compact('model', 'taskModel'));
    }

}
