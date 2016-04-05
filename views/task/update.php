<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $theme array
* @var $params
*/

extract($theme); //$subject, $subjectId, $subtheme, $themeId, $subthemeUrl
if (!isset($subthemeUrl))
	$subthemeUrl = "/theme/view/$subjectId/$themeId";
$this->title = 'Редактирование упражнения' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $subtheme, 'url' => [$subthemeUrl]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-update">

	<span> <?= Html::encode($subject); ?>  </span> <br>
	<!--<span> <?= Html::encode($this->title . " в теме «" . $subtheme . "»"); ?>  </span> <br>-->

    <h1><?= Html::encode($params['invitation']) ?></h1>
    <?= $this->render('_form', compact('model','params')) ?>

</div>
