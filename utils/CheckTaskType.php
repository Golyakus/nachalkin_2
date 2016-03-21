<?php
namespace app\utils;

class CheckTaskType extends ButtonTaskType 
{
	public function getType() { return 'check'; }
	protected function getHtmlAnswerType() { return 'checkbox'; }
	public function getEditTitle()
	{
		return 'Выбор нескольких верных ответов из предложенных вариантов';
	}
	public function getPrototypeFilename() { return TaskType::PROTOTYPE_DIR.'task_check.xml'; }	
}

?>
