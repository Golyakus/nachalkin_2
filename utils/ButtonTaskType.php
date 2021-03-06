<?php
namespace app\utils;

abstract class ButtonTaskType extends TaskType
{
	protected function getHtmlAnswerType() { return $this->getType(); }

	private $localscope = [];

	protected function traverseStart(\SimpleXMLElement $elem, $params)
	{
		$retval = parent::traverseStart($elem, $params);
		if ($elem->getName() == 'task' && $params['action'] == TaskType::RENDER_SOLVE_ACTION)
			$params['model']->setNameSeed();
		return $retval;
	}

	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		extract($params);
		if ($action == TaskType::RENDER_SOLVE_ACTION)
			$model->newAnswerElement();
		$this->localscope['score'] = "0";
		$this->localscope['correct'] = false;
		parent::processAnswerElement($elem, $params);
		$score = $this->localscope['score'];
		$t = $this->getHtmlAnswerType();
		$retval = "<input type=\"$t\" ";
		if ($this->localscope['correct'])
		{			
			if ($action == TaskType::RENDER_VIEW_ACTION)
				$retval .= "checked ";
			else if ($action == TaskType::RENDER_SOLVE_ACTION)
				$model->updateCorrectAnswer($score);
		}
		$name = $action == TaskType::RENDER_SOLVE_ACTION ? $model->getInputElementName() : "";	
		$retval .= " name=\"" . $name . "\" value=\"$score\">" . $elem . '</input><br>';
		if ($action != TaskType::PARSE_ACTION)
			echo $retval;
		return false;
	}
	function __construct()
	{
		parent::__construct();
		/*
		$this->endElement['ans-element'] = function($self, $elem, $params) {
			extract($params);
			if ($action != TaskType::PARSE_ACTION)
				echo '</input><br>';
		};
		*/
	}
	protected function onAttributes(\SimpleXMLElement $elem, $scope, $params)
	{
		parent::onAttributes($elem, $scope, $params);
		extract($scope); // numeric="xxxx" 
		if (isset($correct))
			$this->localscope['correct'] = $correct=='true';
		if (isset($score))
			$this->localscope['score'] = $score;
	}

}

?>


