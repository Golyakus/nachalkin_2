<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Kim */
/* @var $dataProvider ActiveDataProvider for app\models\Task */
/* @var $form yii\widgets\ActiveForm */

?>
<div class="row">
    <div class="col-lg-5">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'kim-form',
                    'options' => ['class' => 'form-horizontal'],]
                    ); 
                $totalScore = 0; $count = 0;
                ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Введите название</label>
                    <div class="col-sm-8">
                        <?= $form->field($model, 'title')->textInput(['class' => 'form-control'])->label(false); ?>
                    </div>
                </div>

                <div class= "form-group">
                    <label class="col-sm-3 control-label">Статус</label>
                    <div class="col-sm-2"><?= $form->field($model, 'status')->dropDownList(\app\models\Kim::$status_strings,['class' => 'form-control'])->label(false) ?></div>
                </div>

                <div class= "form-group">
                    <label class="col-sm-3 control-label">Описание задания</label>
                    <div class="col-sm-8"><?= $form->field($model, 'description')->textArea(['rows'=>6,'cols'=>8,'class' => 'form-control'])->label(false) ?></div>
                    
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Время выполнения задания</label>
                    <div class="col-sm-2"><?= $form->field($model, 'solvetime')->textInput(['type'=>'number','class' => 'form-control'])->label(false) ?></div>
                </div>
                <?= Html::submitButton('Закончить редактирование и сохранить задание', ['class' => 'btn btn-warning', 'name'=>'submitb', 'value' => 'savekim']) ?>
                <?= Html::submitButton('Добавить упражнения', ['class' => 'btn btn-success pull-right', 'name'=>'submitb', 'value' => 'addtask']) ?>
                <?= $form->field($model, 'theme_id',['options' => ['class' => 'hidden']]); ?>
                
            </div>
        </div>
    </div>
    <div class="col-lg-6 ">
        
                <?php if (isset($dataProvider) && ($dataProvider->getTotalCount() != 0)): ?>
                    <?php
                    foreach ($dataProvider->getModels() as $task)
                    {
                        ?>


                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <?= $task->max_score . " баллов за задание" ?>
                                <?= Html::a('удалить', 'deltask?id='.$task->id, ['class' => 'pull-right'])?>
                            </div>
                            <div class="panel-body">

                                
                            <div class="row">
                                <div class = "col-lg-10">
                                    <?php
                                //$form = ActiveForm::begin(['class'=>'col-lg-8']);
                                    $task->taskType->traverse(['model' => $task, 'form' => $form, 'action' => \app\utils\TaskType::RENDER_VIEW_ACTION]);
                                //ActiveForm::end();
                                    echo Html::submitButton('Редактировать упражнение', 
                                        ['class' => 'edit-task-btn btn btn-success', 'name'=>'edittask', 'value' => $task->id]
                                        );          
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                            <?php
                            $totalScore += $task->max_score;
                            $count++;
                        }
                        ?>
                    <?php ActiveForm::end(); ?>
                    <span><?= "Максимальное количество баллов за $count упражнений - $totalScore "?></span>
                    <?php else: {?>
                    <h4>Пока нету добавленных упражнений</h4>    

                    <?php }endif ?>
    </div>
</div> 


