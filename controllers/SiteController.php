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
                        'roles' => ['teacher'],
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
		$dataProvider = new \yii\data\ActiveDataProvider(['query'=>\app\models\Kim::find(['subject_id'=>$subjectId])]);
        $domainProvider = new \yii\data\ActiveDataProvider(['query' => \app\models\Domain::find()->where(1)]);
        // find all classes for current domain
        $query = \app\models\Subject::find()->where(['domain_id' => $subjectModel->domain_id])->orderBy(['class' => SORT_ASC]);
        $classItems = [];
        foreach ($query->all() as $subj)
            $classItems[] = ['label'=> $subj->class, 'url' => '/site/index?subjectId=' . $subj->id];

        return $this->render('index', compact('subjectModel', 'dataProvider', 'domainProvider', 'classItems'));

    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
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
