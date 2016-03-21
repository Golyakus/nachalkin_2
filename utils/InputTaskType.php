<?php
namespace app\utils;

class InputTaskType extends TaskType 
{
	public function makeBehavior()
	{
		return new \app\behaviors\InputTaskBehavior();
	}
	public function getFormTemplate()
	{
		return TaskType::PROTOTYPE_DIR.'inputtype';
	}
	public function getType() { return 'input'; }
	public function getEditTitle()
	{
		return 'Ввод числа или слова в поле для ответа';
	}
	public function getPrototypeFilename() { return TaskType::PROTOTYPE_DIR.'task_input.xml'; }	

	private $localscope = [];
	
	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		$this->localscope['isnumeric'] = false;
		parent::processAnswerElement($elem, $params);
		extract($params); // model action
		if ($this->localscope['isnumeric'])
			$t = 'number';
		else
			$t = 'text';
		if ($action == TaskType::RENDER_SOLVE_ACTION)
		{
			$model->correctAnswer = (string)$elem;
			$name = $model->getInputElementName();
			$retval = "<input type=\"$t\" name=\"$name\"/>";
			echo $retval;
		}
		else if ($action == TaskType::RENDER_VIEW_ACTION)
		{
			echo "<input type=\"$t\" value=\"$elem\"/>";
		}
/*
		else if ($action == TaskType::PARSE_ACTION)
		{
			\Yii::trace('in parsing answer element ', $model->correctAnswer);
			$model->correctAnswer = (string)$elem;
		}
*/
		return false;
	}

	protected function onAttributes(\SimpleXMLElement $elem, $scope, $params)
	{
		parent::onAttributes($elem, $scope, $params);
		extract($scope); // numeric="xxxx" 
		if (isset($numeric))
		{			
			$this->localscope['isnumeric'] = $numeric == 'true';
		}
		if (isset($max_score))
			$params['model']->max_score = $max_score;
	}

	public function checkAnswer($model, $postResponse)
	{
		if (!isset($postResponse[$model->getInputElementName()]))
			return "";
		if ($postResponse[$model->getInputElementName()] == $model->correctAnswer)
			return $model->max_score;
		else
			return "0";
	}


}

?>

