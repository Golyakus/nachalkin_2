<?php
namespace app\utils;

use \yii\base\ViewRenderer;

class TaskRenderer extends ViewRenderer
{
	public function render($view, $file, $param)
	{
		$xml = file_get_contents($file);
		return $this->renderXMLTask(new \SimpleXMLElement($xml), $param);
	}

	public function renderXMLTask( \SimpleXMLElement $task, array $param)
	{
		extract($param); // $params, $tasktype
		$pseudoModel = new \app\models\Task();
		$pseudoModel->setType($tasktype->getType());
		$params['model'] = $pseudoModel;
		$tasktype->render($task, $params);
	}
}	

?>
