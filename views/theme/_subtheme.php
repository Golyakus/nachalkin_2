<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @child $ app\utils\ThemeTreeElement */
?>

<div class="subtheme-list">
<?php
    foreach($child->children as $subtheme)
    {
        echo "<p>TODO: Collapse widget</p>";
        $title = $subtheme->model->title; 
        $total = $subtheme->getTaskCount();
        $solved = $subtheme->getSolvedTaskCount();
        echo "<p>&nbsp; $title &nbsp; $solved из $total</p>";
        foreach ($subtheme->children as $collapsed) 
        {
            $title = $collapsed->model->title; 
            $total = $collapsed->getTaskCount();
            $solved = $collapsed->getSolvedTaskCount();
            echo "<p>==|||&nbsp;&nbsp; $title &nbsp; $solved из $total</p>";
        }

    }
?>
</div>
