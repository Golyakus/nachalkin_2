<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Theme */

$themeId = $model->id;
?>
<div id="themePanel" class="panel panel-default">
     <div class="panel-heading h4">
     	<b class="active-theme-title"><?= $model->title ?></b>	          
     	<div class="pull-right">
     		<a href="<?= "/theme/update/$themeId/1" ?>" class="btn btn-default btn-sm" >Редактировать тему</a>
     		<a href= "<?= "/task/choosetype/1/$themeId" ?>"  class="btn btn-default btn-sm" > Добавить упражнение в тему</a>
     	</div>
     </div>
     <div class="panel-body">
        <p class = "active-theme-descr"> <?= $model->description ?>
			</p>
     </div>
  	</div>

    <!-- TODO: СChange subjectID from 1 to $model->subjectId -->
    <?= HTML::a("Упражнения темы", ["view", "id" => $themeId, "subjectId" => "1"], ["class" => "btn btn-primary btn-lg"]) ?>