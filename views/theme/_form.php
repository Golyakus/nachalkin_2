<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $form yii\widgets\ActiveForm */

//$refer = $_SERVER['HTTP_REFERER'];
?>

<div class="theme-form">

    <?php $form = ActiveForm::begin(); ?>

    <!--<?= $form->field($model, 'created_at')->textInput() ?>
    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'updated_at')->textInput() ?>
    <?= $form->field($model, 'updated_by')->textInput(['maxlength' => true]) ?>-->


    <?= $form->field($model,'title') ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'complexity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary']) ?>
      
    </div>

    <?php ActiveForm::end(); ?>

</div>
