<?php

/* @var $this yii\web\View */


$this->title = 'Редактор задач для КИМ';
$xml_content = $tasks[0]['content'];
$xml = new SimpleXMLElement($xml_content);
Yii::trace($xml_content,'GIG');
Yii::trace($xml[0]->body->getName(),'GIG');
$s = $xml[0]->body->p[0];
//echo $s;
$t = ['Task1', 'Task2', 'Task3'];
$DP = new \yii\data\ArrayDataProvider(['allModels'=>$t, 'pagination'=>false]);

$subject_str = 'математике';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Задачи для КИМ по <?=$subject_str?></h1>

        <p><a class="btn btn-lg btn-success" href="/site/add">Добавить новую задачу</a></p>
    </div>

    <div class="body-content">

	<ul>
	  <li>Task1</li>
	  <li>Task2</li>
	</ul>
       
    </div>
</div>
