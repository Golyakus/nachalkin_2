<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Theme */

?>
<div id="themePanel" class="panel panel-default">
     <div class="panel-heading h4">
     	<b class="active-theme-title"><?= $model->title ?></b>	          
     </div>
     <div class="panel-body">
        <p class = "active-theme-descr"> <?= $model->description ?>
			</p>
     </div>
  	</div>

 