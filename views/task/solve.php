<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Taskresult */
/* @var $taskModel app\models\Task
* @var $params
*/
$subthemeModel = $taskModel->getTheme()->one();
$superThemeModel = \app\models\Theme::findOne($subthemeModel->parent);
//extract($theme); //$subject, $subjectId, $subtheme, $themeId, 
$this->title = 'Решение упражнений';
//$this->params['breadcrumbs'][] = ['label' => $subtheme, 'url' => ["/theme/view/$subjectId/$themeId"]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-update">

	<h1><?= Html::encode($superThemeModel->title . '. ' . $subthemeModel->title) ?></h1>
	<span> <?= Html::encode('Задача №'.$taskModel->id); ?>  </span> <br>  
    <?php $params = ['action'=>\app\utils\TaskType::RENDER_SOLVE_ACTION];
    	$model = $taskModel;
     ?>
    <?= $this->render('_form', compact('model','params')) ?>

</div>
