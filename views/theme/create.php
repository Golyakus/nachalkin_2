<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $subjectId */

$parent = $model->getParent();

if ($parent == null)
{
	$this->title = 'Добавление темы';

}
else 
{
	$this->title = 'Добавление темы в ' . $parent->title;	
	$this->params['breadcrumbs'][] = ['label' => $parent->title, 'url' => ['view', 'id' => $parent->id, 'subjectId'=>$subjectId]];
	$this->params['breadcrumbs'][] = $this->title;
}



?>
<div class="row">
	<div class="theme-create col-sm-6">
	    <div class="panel panel-default">
	        <div class="panel-heading h2"><?= $this->title; ?></div>
	            <div class="panel-body">
				    <?= $this->render('_form', [
				        'model' => $model,
				    ]) ?>
	    	</div>
	    </div>
	</div>
</div>
