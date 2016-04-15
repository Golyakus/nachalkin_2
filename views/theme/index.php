<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model 
/* @var $subjectId */

$this->title = 'Темы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= if (\Yii::$app->user->can('admin-teacher')) Html::a('Добавить тему', ['create', 'subjectId' => $subjectId, 'parentId'=>$model->id], ['class' => 'btn btn-success']) ?>
    </p>
	<p>
		Тема: <?= Html::a($model->title, ['view', 'id' => $model->id, 'subjectId'=> $subjectId], ['class' => 'btn btn-success']) ?>
	</p>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) use ($subjectId) {
            return Html::a(Html::encode($model->title), ['index', 'themeId' => $model->id, 'subjectId'=> $subjectId]);
        },
    ]) ?>

</div>
