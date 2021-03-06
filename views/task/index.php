<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TaskSearchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = "Все задачи из БД";
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'created_at',
            'created_by',
			'updated_at',
			'updated_by',
			'max_score',
			//'condition:ntext:Условие',		
            'content:ntext:Условие',
			'theme_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
	
    ]); ?>

</div>
