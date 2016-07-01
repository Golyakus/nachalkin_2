<?php
namespace app\utils;

class DropdownTaskType extends TaskType 
{
	private $localscope = [];

	public function makeBehavior()
	{
		return new \app\behaviors\RadioTaskBehavior();
	}

	public function getType() { return 'dropdown'; }
	
	public function getEditTitle()
	{
		return 'Выбор одного верного варианта из выпадающего списка';
	}
	
	public function getPrototypeFilename() 
	{ 
		return Self::getFullPrototypeDir() . 'task_dropdown.xml'; 
	}
	
	public function getFormTemplate()
	{
		return Self::getFullPrototypeDir() . 'task_dropdown.php';
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


	/** old stuff
	*/
	protected function traverseStart(\SimpleXMLElement $elem, $params)
	{
		extract($params);
		parent::traverseStart($elem, $params);
		if ($elem->getName() == 'answer' && $action != TaskType::PARSE_ACTION)
		{
			echo "$elem<select";
			if ($action == TaskType::RENDER_SOLVE_ACTION)
				echo ' name="'. $model->getInputElementName() . '"';
			echo '>';
			if ($action == TaskType::RENDER_SOLVE_ACTION)
				echo '<option value="0" />';
		}
		$this->localscope['max_score'] = "10";
		parent::processAnswerElement($elem, $params);
		$model->max_score = $this->localscope['max_score'];
		return true;
	} 

	protected function traverseEnd(\SimpleXMLElement $elem, $params)
	{
		if ($elem->getName() == 'answer' && $params['action'] != TaskType::PARSE_ACTION)
			echo '</select>';
		else if ($elem->getName() == 'ans-element' && $params['action'] != TaskType::PARSE_ACTION)
			echo '</option>';

		parent::traverseEnd($elem, $params);
	}

	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		extract($params);
		$this->localscope['correct'] = false;
		$this->localscope['score'] = '0';
		parent::processAnswerElement($elem, $params);
		$opt = '<option';
		if ($action == TaskType::RENDER_SOLVE_ACTION)
		{
			$score = $this->localscope['correct'] ? $model->max_score : $this->localscope['score'];
			$opt .= ' value="' . $score . '" ';
		}
		else if ($this->localscope['correct'] && $action == TaskType::RENDER_VIEW_ACTION) 
			$opt .= ' selected ';

		$opt .= ">";
		if ($action != TaskType::PARSE_ACTION)
			echo $opt;
		return true;
	}	

	protected function onAttributes(\SimpleXMLElement $elem, $scope, $params)
	{
		parent::onAttributes($elem, $scope, $params);
		extract($scope);
		if (isset($correct))
			$this->localscope['correct'] = $correct=='true';
		if (isset($score))
			$this->localscope['score'] = (string)$score;
		if (isset($max_score))
			$this->localscope['max_score'] = (string)$max_score;
	}
	// end of old stuff
}

?>
