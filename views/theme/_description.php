<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $subjectId	*/

$themeId = $model->id;
?>
<div id="themePanel" class="panel panel-default">
     <div class="panel-heading h4">
     	<b class="active-theme-title"><?= $model->title ?></b>	          
     	<div class="pull-right">
     		<a href="<?= "/theme/update/$themeId/$subjectId" ?>" class="btn btn-default btn-sm btn-panel-heading" >Редактировать тему</a>
     		<a href= "<?= "/task/choosetype/$subjectId/$themeId" ?>"  class="btn btn-default btn-sm btn-panel-heading" > Добавить упражнение в тему</a>
     	</div>
     </div>
     <div class="panel-body">
        <p class = "active-theme-descr"> <?= $model->description ?>
			</p>
     </div>
  	</div>

    <?= HTML::a("Упражнения темы", ["/theme/view", "id" => $themeId, "subjectId" => $subjectId], ["class" => "btn btn-primary btn-lg"]) ?>
