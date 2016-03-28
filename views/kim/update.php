<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Kim */
/* @var $dataProvider */

$this->title = 'Редактирование задания ' . ' "' . $model->getName() . '"';
//$this->params['breadcrumbs'][] = ['label' => 'Kims', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование задания';
?>
<div class="kim-update">

	<span><?= app\models\Subject::getSubjectName($model->subject_id) ?></span>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'dataProvider' => $dataProvider
    ]) ?>

</div>
