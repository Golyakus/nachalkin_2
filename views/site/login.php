<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Добро пожаловать!';
?>
<div class="site-login">
    <div class="block-center mt-xl wd-xl">
         <!-- START panel-->
         <div class="panel panel-dark panel-flat">
            <div class="panel-heading text-center">
            <h4> Началкин  </h4>
            </div>
            <div class="panel-body">
               <p class="text-center pv">Введите ваш email и пароль</p>
                   <?php $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'options' => ['class' => ' mb-lg']
                        ]
                    ); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label(false)?>

                        <?= $form->field($model, 'password')->passwordInput()->label(false) ?>

                        <?= $form->field($model, 'rememberMe')->checkbox()->label("Запомнить меня") ?>

                        <div class="form-group">
                                <?= Html::submitButton('Войти', ['class' => 'btn btn-block btn-primary mt-lg', 'name' => 'login-button']) ?>
                        </div>
                    <?php ActiveForm::end(); ?>
            </div>
         </div>
      </div>
</div>
