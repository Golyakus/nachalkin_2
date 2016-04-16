<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Kim */

function generateNestable($treeElement)
{
	echo '<ol class="dd-list">';
	foreach ($treeElement->children as $th)
	{
		echo '<li data-id="'.$th->model->id . '" class="dd-item">';
		echo '<div data-theme-id="' . $th->model->id . '" class="dd-handle"> ' . $th->model->title . '</div>';
		if (!$th->isLeaf)
			generateNestable($th);
		echo '</li>';
	}
	echo '</ol>';
}

$subjectId = $model->subject_id;
$userId = \Yii::$app->user->id;

$this->title = 'Добавление упражнений в задание';
$this->params['breadcrumbs'][] = ['label' => $model->getName(), 'url' => ['/kim/update?id=' . $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="kim-div" data-kim-id=<?= $model->theme_id ?> class="kim-addtask">

	<h3><?= app\models\Subject::getSubjectName($model->subject_id) ?></h3>
    <h1><?= Html::encode($this->title . ' "' . $model->getName().'"')  ?></h1>

 	<div class="row">
		<div class="col-lg-4">
			<h3>Темы</h3>

			<div class="js-nestable-action"><a data-action="expand-all" class="btn btn-default btn-sm mr-sm">Развернуть все</a><a data-action="collapse-all" class="btn btn-default btn-sm">Свернуть все</a>
			 
   			</div>
   			
   			<div id="nestable" class="dd">
   				<?php
   					generateNestable(\app\models\Subject::getThemeTree($subjectId, $userId));
   				?>
   			</div>
        
    	</div>
      	
		<div class="col-lg-offset-1 col-lg-7">
			
			<h3 class="page-heading">Описание темы</h3>
			<div id="theme-descr" data-route="description" data-subject="<?=$subjectId?>"><?= $this->render('_description', [
					'model'=>\app\models\Subject::findModel($subjectId)->getTheme()->one(), 'subjectId' => $subjectId
				]); ?>
			</div>
        </div>
	</div>
	<?php
		$form = ActiveForm::begin();
	?>
	<div id ="theme-task-header"><b class="active-theme-title">Выберите упражнения из темы</b></div>
	<?php
		echo Html::submitButton('Закончить добавление и посмотреть задание', 
			['class' => 'btn btn-success', 'name' => \app\models\Kim::KIM_ADDTASK_BUTTON_NAME]);		
		ActiveForm::end();
	?>
</div>
