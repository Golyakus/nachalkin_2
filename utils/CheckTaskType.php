<?php
namespace app\utils;

class CheckTaskType extends ButtonTaskType 
{
	public function makeBehavior()
	{
		return new \app\behaviors\CheckTaskBehavior();
	}
	public function getType() { return 'check'; }
	protected function getHtmlAnswerType() { return 'checkbox'; }
	public function getEditTitle()
	{
		return 'Выбор нескольких верных ответов из предложенных вариантов';
	}
	public function getPrototypeFilename() { return Self::getFullPrototypeDir() . 'task_check.xml'; }	
	public function getFormTemplate()
	{
		return Self::getFullPrototypeDir() . 'task_check.php';
	}

	public function checkAnswer($model, $postResponse)
	{
		return $model->analyseAnswer($model->max_score, $postResponse);
	}

}

?>
