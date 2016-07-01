<?php
namespace app\utils;

class RadioTaskType extends ButtonTaskType 
{
	public function makeBehavior()
	{
		return new \app\behaviors\RadioTaskBehavior();
	}

	public function getType() { return 'radio'; }
	public function getEditTitle()
	{
		return 'Выбор одного верного ответа из предложенных вариантов';
	}
	public function getPrototypeFilename() { return Self::getFullPrototypeDir() . 'task_radio.xml'; }	
	public function getFormTemplate()
	{
		return Self::getFullPrototypeDir() . 'task_radio.php';
	}


	public function checkAnswer($model, $postResponse)
	{
		if (!isset($postResponse[$model->getInputElementName()]))
			return "";
		if ($postResponse[$model->getInputElementName()] == $model->correctAnswer)
			return $model->max_score;
		else
			return $postResponse[$model->getInputElementName()];
	}

}

?>
