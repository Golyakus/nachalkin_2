<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Kim */
/* @var $dataProvider ActiveDataProvider for app\models\Task */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="kim-form">

    <?php $form = ActiveForm::begin(); 
        $totalScore = 0; $count = 0;
    ?>

    <div class= "row">
        <div class="col-lg-2"><span>Введите название</span></div>
        <div class="col-lg-8"><?= $form->field($model, 'title')->textInput()->label('') ?></div>
    </div>  

    <div class= "row">
        <div class="col-lg-2"><span>Статус</span></div>
        <div class="col-lg-2"><?= $form->field($model, 'status')->dropDownList(\app\models\Kim::$status_strings)->label('') ?></div>
    </div>

    <div class= "row">
        <div class="col-lg-2"><span>Описание задания</span></div>
        <div class="col-lg-8"><?= $form->field($model, 'description')->textArea(['rows'=>6,'cols'=>8])->label('') ?></div>
        
    </div>
    <div class="row">
        <div class="col-lg-2"><span>Время выполнения задания</span></div>
        <div class="col-lg-1"><?= $form->field($model, 'solvetime')->textInput(['type'=>'number'])->label('') ?></div>
    </div>

    <?php if (isset($dataProvider)): ?>
        <span class="col-lg-12"><h4>Все упражнения этого задания</h4></span>
        <?php
            foreach ($dataProvider->getModels() as $task)
            {
        ?>
                <div class="row">
                <div class="col-lg-1"> <?= $task->max_score . " баллов" ?></div>
                <div class="col-lg-offset-8"><?= Html::a('delete', 'deltask?id='.$task->id)?></div>
                </div>
                <div class="row">
                    <div class = "col-lg-10">
                        <span><?= $task->id . '.' ?></span>
                        <?php
                            //$form = ActiveForm::begin(['class'=>'col-lg-8']);
                            $task->taskType->traverse(['model' => $task, 'form' => $form, 'action' => \app\utils\TaskType::RENDER_VIEW_ACTION]);
                            //ActiveForm::end();
                            echo Html::submitButton('Редактировать упражнение', 
                                    ['class' => 'btn btn-success', 'name'=>'edittask', 'value' => $task->id]
                            );          
                        ?>
                    </div>
                </div>
        <?php
                $totalScore += $task->max_score;
                $count++;
            }
        ?>
    <?php endif ?>

    <div class="row"><div class="col-offset-lg-2">
         <?= Html::submitButton('Добавить упражнения', ['class' => 'btn btn-success', 'name'=>'submitb', 'value' => 'addtask']) ?>
    </div></div>

    <div class="row"><div class="col-offset-lg-2">
         <span><?= "Максимальное количество баллов за $count упражнений - $totalScore "?></span>
    </div></div>


     <div class="row"><div class="col-offset-lg-2">
        <?= Html::submitButton('Сохранить задание', ['class' => 'btn btn-success', 'name'=>'submitb', 'value' => 'savekim']) ?>
    </div></div>

    <?= $form->field($model, 'theme_id')->hiddenInput()->label(''); ?>
    <?php ActiveForm::end(); ?>
</div>
