<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Theme */
/* @var $subjectId */

$this->title = 'Редактирование темы: "' . $model->title .'"' ;
//$this->params['breadcrumbs'][] = ['label' => 'Themes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id, 'subjectId'=>$subjectId]];
$this->params['breadcrumbs'][] = $this->title;
?>


    
            

<div class="theme-update">

<p>  
    <el class="h2"> <?= \app\models\Subject::getSubjectName($subjectId) ?>  </el>
    <?=  Html::a('Добавить подтему', ['create', 'parentId' => $model->id, 'subjectId'=>$subjectId]) ?> 
</p>
    

    
    <div class="row">
        <div class="theme-create col-sm-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                        <?php
                            if ($model->canBeDeleted())
                                echo Html::a('Удалить тему', ['delete', 'id' => $model->id, 'subjectId' => $subjectId],
                                [
                                    'class' => 'btn btn-danger pull-right',
                                    'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить эту тему?',
                                    'method' => 'post',
                                    ],
                                ]) 
                        ?>

                        <h3> Редактирование темы: </h3>
                        <h3> <?= $model->title; ?> </h3>
                        
                </div>
                <div class="panel-body">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>


</div>
