<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $form yii\widgets\ActiveForm */
?>
  <div> <h4>Условие</h4>
   


   <div> <h4>Ответ</h4>
   <?= $form->field($model, 'answerprefix')->textInput() ?>
   <?= $form->field($model, 'answervalue')->textInput(['type'=> ($model->isnumeric ? 'number': 'text')]) ?>
   <?= $form->field($model, 'answersuffix')->textInput() ?>
   <?= $form->field($model, 'solution')->textArea(['rows' => 4]) ?>
   </div>
	<div>
		<!--<?php $form = ActiveForm::begin(['layout'=>'horizontal']); ?> -->
   		<?= $form->field($model, 'isnumeric')->radioList([true=>'Ответ - число', false=>'Ответ - слово']) ?>
		<?= $form->field($model, 'max_score')->textInput(['label'=>'Баллов за правильный ответ:', 'isnumeric' => true]) ?>
 		<!--<?php ActiveForm::end(); ?>-->
	</div>




