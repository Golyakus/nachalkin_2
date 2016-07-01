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
		return Self::getFullPrototypeDir() . 'task_input.php';
	}
	public function getType() { return 'input'; }
	public function getEditTitle()
	{
		return 'Ввод числа или слова в поле для ответа';
	}
	public function getPrototypeFilename() { return Self::getFullPrototypeDir() . 'task_input.xml'; }

	protected function processModel(array $elements, \app\models\Task $model, $action)
	{
		parent::processModel($elements, $model, $action);
		$model->max_score = $elements['ans_element']['max_score'];
		if ($action == TaskType::RENDER_SOLVE_ACTION)
			$model->correctAnswer = $elements['ans_element']['text'];
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

	// old stuff for version 0


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
			$answer_length = strlen($elem) + 1; 
			$retval = "<input class='task-form-input' style='width:$answer_length"."em;'"."name=\"$name\" />";
			echo $retval;
		}
		else if ($action == TaskType::RENDER_VIEW_ACTION)
		{
			
			$answer_length = strlen($elem) + 1;

			echo "<input class='task-form-input' maxlength=$answer_length style='width:$answer_length"."em'"."value=$elem disabled/>";
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

}

?>

