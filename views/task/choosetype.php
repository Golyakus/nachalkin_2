<?php

use yii\helpers\Html;
/**
* @var $this yii\web\View 
* @var array $tasktypes	
* @var array $theme
						*/	

extract($theme);  //$subject, $subjectId, $subtheme, $themeId, 
$this->title = 'Добавление упражнения';
$this->params['breadcrumbs'][] = ['label' => $subtheme, 'url' => ["/theme/view/$subjectId/$themeId"]];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="task-index">
	<span> <?= Html::encode("$subject"); ?>  </span> <br>
	<span> <?= Html::encode($this->title . " в тему «". $subtheme. "»"); ?>  </span> <br>
	<h2><?= Html::encode('Выберите тип упражнения') ?></h2>
	<div>
		<ol type="1">
			<?php
				for ($i=0; $i < count($tasktypes); $i++) {
					$task = $tasktypes[$i];
					$type = $task->getType();
					$title = $task->getEditTitle();
					echo '<div class = "row">';
					echo '<div class="col-sm-6">';
					echo '<div class="panel panel-default">';		
			        echo '<div class="panel-heading">';  	
			        	echo "$title";

			        echo "</div>"; 
			        echo '<div class="panel-body">';
			        $url = \Yii::$app->urlManager->createUrl("task/newtask/$type/$subjectId/$themeId");
					$this->render($task->getPrototypeFilename(), ['params'=>['action'=>\app\utils\TaskType::RENDER_VIEW_ACTION], 'tasktype'=>$task]);
					echo "<a class='btn btn-success pull-right' href='$url'>Добавить задачу этого типа</a>";
			        echo '</div>';
			        echo '</div>';
					echo '</div>';

					echo '</div>';
					echo '</div>';
					/*<!--
					echo "<li>$title";
					echo '<div class="row">';
					$url = \Yii::$app->urlManager->createUrl("task/newtask/$type/$subjectId/$themeId");
					$this->render($task->getPrototypeFilename(), ['params'=>['action'=>\app\utils\TaskType::RENDER_VIEW_ACTION], 'tasktype'=>$task]);
					echo "<p><a class='btn btn-default' href='$url'>Добавить задачу этого типа</a></p>";
					echo '</div>';
					echo "</li>\n"; -->  */ 
				}
			?>
		</ol> 
	</div>
</div>
