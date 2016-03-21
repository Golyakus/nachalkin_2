<?php

use yii\helpers\Html;

function generateNestable($treeElement)
{
	echo '<ol class="dd-list">';
	foreach ($treeElement->children as $th)
	{
		echo '<li data-id="'.$th->model->id . '" class="dd-item">';
		echo '<div data-theme-id="' . $th->model->id . '" class="dd-handle"> ' . $th->model->title . '</div>';
		if (!$th->isLeaf)
			generateNestable($th);
		echo '</li>';
	}
	echo '</ol>';
}

//TODO: change model from theme with id = 1 to subject

$model = \app\models\Subject::findModel(1)->getTheme()->one();
?>
<!--
	<div>
		<a href="/site/index?id=1" class="btn btn-default"><?= $model->title ?></a>
		<a href="/site/index?id=1" class="btn btn-default"><?= $model->title ?></a>
		<a href="/site/index?id=1" class="btn btn-default"><?= $model->title ?></a>
	</div>
	 <select id="grade" class="form-control">
  		<option >4 класс</option>
  		<option >4 класс</option>
  		<option >4 класс</option>
	</select> 
-->
	<div class="row">
		<div class="col-lg-4">
			<h3>Темы</h3>

			<div class="js-nestable-action"><a data-action="expand-all" class="btn btn-default btn-sm mr-sm">Развернуть все</a><a data-action="collapse-all" class="btn btn-default btn-sm">Свернуть все</a>
			 
       <?= HTML::a("Добавить новую тему", ['/theme/create','subjectId' => $model->id, 'parentId' => $model->id], ['class' => 'btn btn-default btn-sm']) ?>
   			</div>
   			
   			<div id="nestable" class="dd">
   				<?php
   					generateNestable(\app\models\Subject::getThemeTree($subjectId, 102));
   				?>
   			</div>
        
    </div>
      	
		<div class="col-lg-offset-1 col-lg-7">
			
			<h3 class="page-heading">Описание темы</h3>
			<div id="theme-descr"><?= $this->render('/theme/_description', ['model'=>$model]); ?></div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<h3>Задания для 4 класса. Математика</h3> 
			<div class="panel panel-default">
            	<div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped">

                          <thead>
                             <tr>
                                <th>id</th>
                                <th>Название</th>
                                <th>Статус</th>
                                <th>Количество упражнений</th>
                                <th>Дата изменения</th>
                                <th></th>
                             </tr>
                          </thead>
                          <tbody>
                             <tr>
                                <td>1</td>
                                <td>КИМ Весна 2015</td>
                                <td>черновик</td>
                                <td>15</td>
                                <td>01.02.2013</td>
                                <td><em class="icon-pencil"></em></td>
                             </tr>
                             <tr>
                                <td>2</td>
                                <td>Контрольная работа — 4 февраля</td>
                                <td>готов к выдаче детям</td>
                                <td>01.02.2013</td>
                                <td><em class="icon-pencil"></em></td>
                             </tr>
                             <tr>
                                <td>3</td>
                                <td>Контрольная работа — 4 февраля</td>
                                <td>готов к выдаче детям</td>
                                <td>01.02.2013</td>
                                <td><em class="icon-pencil"></em></td>
                             </tr>
                          </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-primary btn-lg">Добавить новое задание</a>
		</div>				
	</div>
