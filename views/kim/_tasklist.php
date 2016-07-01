<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $dataProvider with app\models\Task */
/* @var $kimThemeid */

?>
	<b class="active-theme-title">Выберите упражнения из темы <?= '"' . $model->title . '"' ?></b>	          
	</div>
	<?php
		$count = 1;
		foreach ($dataProvider->getModels() as $task)
		{ 
	?>
	<div class="row">
		<div class = "col-lg-5">
			<div class="panel panel-default">
				<div class="panel-heading">
					<?= 'Задание №' . $task->id ?>
				</div>
				<div class="panel-body">
					
					<?php
						$id = $task->id;
						$js = "\$('#$id').load('addt?id=$id&themeId=$kimThemeId')";
						//$form = ActiveForm::begin();
		        		echo $task->taskType->traverse(['model' => $task, 'view' => $this, 'showsolution' => true,  'action' => \app\utils\TaskType::RENDER_VIEW_ACTION]);
		        		//ActiveForm::end();
		        		if (isset($kimTasks[$id]))
		        		{
		        			$caption = 'Добавлено в задание';
		        			$class = '"btn add-btn btn-success"';
		        			$onclick = '';
		        		}
		        		else
		        		{
		        			$caption = 'Добавить в задание';
		        			$class = '"btn add-btn btn-primary"';
		        			$onclick = "onclick=\"$js\""; 
		        		}
		        		echo "<span id=\"$id\" class=$class $onclick>$caption</span>";     		
		        	?>
		        </div>
			</div>	
		</div>
	</div>
	<?php
			$count++;
		}
	?>


 