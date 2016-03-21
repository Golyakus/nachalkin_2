<?php

use yii\helpers\Html;
use limion\jqueryfileupload\JQueryFileUpload;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Task */
/* @var $params array
/* @var $form yii\widgets\ActiveForm */
?>

<div class="task-form">
	<span>Тип задания: <?= $model->taskType->getEditTitle() ?></span>
    <?php $form = ActiveForm::begin(); 
        extract($params);  
        $model->taskType->traverse(compact('model', 'form', 'action')); 
        /*
        echo JQueryFileUpload::widget([
        'name' => 'files[]',
        'url' => ['upload'], // your route for saving images,
        'appearance'=>'ui', // available values: 'ui','plus' or 'basic'
        'formId'=>$form->id,
        'options' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000,
            'dataType' => 'json',
            'acceptFileTypes'=>new yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'autoUpload'=>false
        ]
        ]);
        */
        echo '<div id="files" class="files"></div>';

        echo limion\jqueryfileupload\JQueryFileUpload::widget([
        'url' => ['upload'], // your route for saving images,
        'appearance'=>'basic', // available values: 'ui','plus' or 'basic'
        'name' => 'files[]',
        'options' => [
            'accept' => 'image/*'
        ],
        'clientOptions' => [
            'maxFileSize' => 2000000,
            'dataType' => 'json',
            'acceptFileTypes'=>new yii\web\JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'autoUpload'=>true
        ],
        'clientEvents' => [
            'done'=> "function (e, data) {
                $.each(data.result.files, function (index, file) {
                    var imgtag = '<img src=\"' + file.url + '\" alt=\"picture\" />';
                    var ta = $('#task-content'),
                    p = ta[0].selectionStart,
                    text = ta.val();
                    if(p != undefined)
                        ta.val(text.slice(0, p) + imgtag + text.slice(p));
                    else{
                        ta.trigger('focus');
                        range = document.selection.createRange();
                        range.text = imgtag;
                    }
                });
            }",
            'progressall'=> "function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }"
        ]
        ]);
        if ($action == \app\utils\TaskType::RENDER_EDIT_ACTION): ?>  
        <?= Html::submitButton('Закончить редактирование упражнения', ['class' => 'btn btn-success']) ?>
    <?php else: ?>
        <?= Html::submitButton('Изменить упражнение', ['class' => 'btn btn-success', 'name'=>\app\models\Task::EDIT_SUBMIT_BUTTON , 'value'=>$model->content]) ?>
        <?= Html::submitButton('Всё верно, сохранить упражнение', ['class' => 'btn btn-success' , 'name'=>\app\models\Task::SAVE_SUBMIT_BUTTON, 'value'=>$model->content]) ?>
    <?php endif ?>

   <!-- <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>  -->

   <!-- </div> -->

    <?php ActiveForm::end(); ?>

</div>
