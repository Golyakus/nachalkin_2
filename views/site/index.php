<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Предметы и темы';
$this->params['breadcrumbs'][] = $this->title;


/* @var $this yii\web\View */
/* @var $dataProvider provider for app\models\Kim */
/* @var $domainDataProvider for app/models/Domain */
/* @var $subjectModel \app\models\Subject */
/* @var $classItems  array of pairs [label, url] for dropdown button*/

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

$subjectId = $subjectModel->id;
$model = $subjectModel->getTheme()->one();
$firstSubthemeModel =  \app\models\Theme::find()->where(['parent' => $model->id])->one();
if (!$firstSubthemeModel) $firstSubthemeModel = $model;
?>

	<?= $this->render('_subjects', compact('domainProvider', 'subjectModel', 'classItems')) ?>
	
	<div class="row">
		<div class="col-lg-4">
			<h3>Темы</h3>

			<div class="js-nestable-action"><a data-action="expand-all" class="btn btn-default btn-sm mr-sm">Развернуть все</a><a data-action="collapse-all" class="btn btn-default btn-sm">Свернуть все</a>
			 
       <?= HTML::a("Добавить новую тему", ['/theme/create','subjectId' => $subjectId, 'parentId' => $model->id], ['class' => 'btn btn-default btn-sm']) ?>
   			</div>
   			
   			<div id="nestable" class="dd">
   				<?php
   					generateNestable(\app\models\Subject::getThemeTree($subjectId, \Yii::$app->user->id));
   				?>
   			</div>
        
    </div>
      	
		<div class="col-lg-offset-1 col-lg-7">
			
			<h3 class="page-heading">Описание темы</h3>
			<div id="theme-descr" data-route="/theme/description" data-subject="<?=$subjectId?>"><?= $this->render('/theme/_description', ['model'=>$firstSubthemeModel, 'subjectId'=>$subjectId]); ?></div>
        </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
      <h3>Задания для предмета "<?= \app\models\Subject::getSubjectName($subjectId) ?>"</h3>

      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            'id',
            'title:ntext',
            [
              'attribute' => 'status',
              'content' => function($kim) { return \app\models\Kim::$status_strings[$kim->status]; }
            ],
            [
              'label' => 'Количество упражнений',
              'content' => function($kim) { return $kim->taskNumber(); }
            ],
            'updated_at',
            [
              'class' => 'yii\grid\ActionColumn',
              'template' => '{update} {delete}',
              'buttons' => [
                'update' => function ($url, $model, $key){ return '<a href="/kim/update/'.$model->id . 
                '" title="Редактировать" aria-label="Редактировать" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>'; },
                'delete' => function ($url, $model, $key) { return '<a href="/kim/delete/'.$model->id .
                  '" title="Удалить" aria-label="Удалить" data-confirm="Вы уверены, что хотите удалить этот КИМ?" '.
                   'data-method="post" data-pjax="0"><span class="glyphicon glyphicon-trash"></span></a>';
                }
              ],
            ],
        ],
    ]); ?>

    <!--
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
            -->
            <a href= <?= "/kim/create/$subjectId" ?> class="btn btn-primary btn-lg">Добавить новое задание</a>

		</div>				
	</div>
