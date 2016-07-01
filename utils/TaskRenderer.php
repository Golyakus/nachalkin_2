<?php
namespace app\utils;

use \yii\base\ViewRenderer;

class TaskRenderer extends ViewRenderer
{
	/**
		$param[const $action, TaskType $task]
	*/
	public function render($view, $file, $param)
	{
		$xml = file_get_contents($file);
		//$param['view'] = $view;
		return $this->renderXMLTask(new \SimpleXMLElement($xml), $param);
	}

	/**
		$param[const $action, TaskType $task]
	*/
	public function renderXMLTask( \SimpleXMLElement $task, array $param)
	{
		extract($param); // $params, $tasktype
		$pseudoModel = new \app\models\Task();
		$pseudoModel->setType($tasktype->getType());
		$params['model'] = $pseudoModel;
		return $tasktype->render($task, $params);
	}
}	

?>
