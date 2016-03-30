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
	<h2> <?= Html::encode("$subject"); ?>  <br>
	 <?= Html::encode($this->title . " в тему «". $subtheme. "»"); ?>  </h2> 
	<h3><?= Html::encode('Выберите тип упражнения') ?></h3>

			<?php
				$row_is_opened = false;
				for ($i=1; $i < count($tasktypes) + 1; $i++) {
					$task = $tasktypes[$i-1];
					$type = $task->getType();
					$title = $task->getEditTitle();
					$url = \Yii::$app->urlManager->createUrl("task/newtask/$type/$subjectId/$themeId");
					
					//close row on every 4th panel
					if ((($i-1)%3 == 0) && $row_is_opened){
						$row_is_opened = false;
						echo "</div>";
					}
					//open row on 0 and every 3d panel
					if (($i-1)%3 == 0){
						echo "<div class='row'>";						
					}

					echo "<div class='col-md-4'>";
					echo "<div class='panel panel-default'>";
					echo "<div class='panel-heading'>";
					echo "<b>$i.$title</b>";
					echo "</div>";
					echo "<div class='panel-body'>";	
					$this->render($task->getPrototypeFilename(), ['params'=>['action'=>\app\utils\TaskType::RENDER_VIEW_ACTION], 'tasktype'=>$task]);
					echo "<a class='btn btn-default btn-success' href='$url'>Добавить задачу этого типа</a>";
					echo "</div> </div> </div>";

					$row_is_opened = true;

					
				}
			?>
</div>
