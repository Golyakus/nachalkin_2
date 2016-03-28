<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Kim */

$this->title = 'Добавление задания';
//$this->params['breadcrumbs'][] = ['label' => 'Kims', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kim-create">

	<span><?= app\models\Subject::getSubjectName($model->subject_id) ?></span>
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
