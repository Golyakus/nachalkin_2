<?php

namespace app\modules\pupil\controllers;

use yii\web\Controller;

/**
 * Default controller for the `pupil` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
		if (\Yii::$app->user->isGuest)
			return redirect('/site/login');
        return $this->render('index');
    }
}
