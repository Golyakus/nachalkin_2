<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Kim */

$this->title = 'Добавление задания';
//$this->params['breadcrumbs'][] = ['label' => 'Kims', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kim-create">

	<h1><?= app\models\Subject::getSubjectName($model->subject_id) ?></h1>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
