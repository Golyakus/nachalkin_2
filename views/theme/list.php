<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */
/* @var $themeId
/* @var $subjectId */

$this->title = 'Список тем. ' . \app\models\Subject::getSubjectName($subjectId);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theme-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>Insert Nav bar with 'Список тем.' 'Задания', 'Сводка результатов'</p>
    <?php
        foreach ($dataProvider->models as $child) {
            echo '<div>';
            echo '<p>' . $child->model->title . '</p>';
            echo $this->render('_subtheme', ['child'=>$child]);
            echo '</div>';
        }

            
    
    ?>

</div>
