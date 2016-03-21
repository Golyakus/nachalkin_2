<?php

use yii\helpers\Html;

/*
* @var $this yii\web\View 
* @var $model app\models\Task 
* @var $theme array
* @var $params
*/

extract($theme); //$subject, $subjectId, $subtheme, $themeId, 
$this->title = 'Добавление упражнения';
$this->params['breadcrumbs'][] = ['label' => $subtheme, 'url' => ["/theme/view/$subjectId/$themeId"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-create">
	<span> <?= Html::encode($subject); ?>  </span> <br>
	<span> <?= Html::encode($this->title . " в тему «" . $subtheme . "»"); ?>  </span> <br>
    <h1><?= Html::encode($params['invitation']) ?></h1>
    <?= $this->render('_form', compact('model','params')) ?>

</div>
