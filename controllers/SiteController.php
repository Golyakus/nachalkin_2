<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout', 'error', 'contact', 'about'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login', 'error', 'contact', 'about'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],               
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['teacher', 'task-editor'],
                    ],               
				],
            ],
/*
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
*/
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex($subjectId=1)
    {
		if (\Yii::$app->user->isGuest)
			return $this->redirect('/site/login');
        $subjectModel = \app\models\Subject::findOne($subjectId);
        if (!$subjectModel)
            throw new \yii\web\NotFoundHttpException("Subject with id=$subjectId not found");
		$dataProvider = new \yii\data\ActiveDataProvider(['query'=>\app\models\Kim::find()->where(['subject_id'=>$subjectId])]);
        $domainProvider = new \yii\data\ActiveDataProvider(['query' => \app\models\Domain::find()->where(1)]);
        // find all classes for current domain
        $query = \app\models\Subject::find()->where(['domain_id' => $subjectModel->domain_id])->orderBy(['year_id' => SORT_ASC]);
        $classItems = [];
        foreach ($query->all() as $subj)
            $classItems[] = ['label'=> $subj->year_id . ' класс', 'url' => '/site/index?subjectId=' . $subj->id];

        return $this->render('index', compact('subjectModel', 'dataProvider', 'domainProvider', 'classItems'));

    }

    private function goIniDestination()
    {
    	if (\Yii::$app->user->can('admin') || \Yii::$app->user->can('teacher') || \Yii::$app->user->can('admin-teacher') || \Yii::$app->user->can('task-editor'))
           	return $this->goHome();
        else if (\Yii::$app->user->can('pupil'))
           	return $this->redirect("/pupil/default/index");
        else
        	throw NotFoundHttpException("Пользователь не найден");
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
        	return $this->goIniDestination();

        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goIniDestination();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
}
