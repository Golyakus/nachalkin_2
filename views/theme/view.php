<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $subjectId */
/* @var $dataProvider yii\data\ActiveDataProvider */

if ($model->parent==null)
{
	$url = 'subject';
	$label = 'Предметы';
}
else
{
	$parentId = $model->parent;
	$url = ['view', 'subjectId' => $subjectId, 'id'=> $parentId];
	$label = $model->getParent()->title;
}

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => $label, 'url' => $url];
$this->params['breadcrumbs'][] = $this->title;
$themeId = $model->id;
?>


<div class="row">
	<div class ="col-sm-12"> <h2><?= Html::encode($this->title) ?></h2> </div>
    <div class="col-sm-4">

        <div class="panel panel-default">
            <div class="panel-heading">				   
				    <?=  Html::a('Редактировать тему', ['update', 'id' => $model->id, 'subjectId'=>$subjectId], ["class" => "pull-right"]) ?> 
				     <el class="h3"> Описание темы </el>
			</div>
			<div class="panel-body">
				<p><?= $model->description ?> </p>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
        <div class="panel panel-default">
            <div class="panel-heading">

            		<?= Html::a('Добавить упражнение в тему', "/task/choosetype/$subjectId/$themeId", ["class" => "pull-right"]) ?>
				    <el class="h3"> Все упражнения темы </el>
			</div>
		</div>
				<?= ListView::widget([
			        'dataProvider' => $dataProvider,
			        'itemOptions' => ['class' => 'item'],
			        'itemView' => function ($model, $key, $index, $widget) use ($subjectId, $themeId) {
			        	echo '<div class="task-panel panel panel-default">';
			        	echo '<div class="panel-body">';
			        	echo '<div class="col-sm-8">';
			        	$form = ActiveForm::begin();
						$model->taskType->traverse(['model'=>$model, 'form'=>$form, 'action'=>\app\utils\TaskType::RENDER_VIEW_ACTION]);
						ActiveForm::end();
						$id = $model->id;

						echo '</div>';
						echo '<div class="col-sm-4">';
			        	echo Html::a('Редактировать', \Yii::$app->urlManager->createUrl("task/update/$id/$subjectId"), ['class' => 'btn btn-sm btn-success pull-right']);
						
			            echo '</div> </div> </div>';
						
			        },
			    ]) ?>

	</div>
</div>
