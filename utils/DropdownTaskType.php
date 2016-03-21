<?php
namespace app\utils;

class DropdownTaskType extends TaskType 
{
	public function makeBehavior()
	{
		return new \app\behaviors\InputTaskBehavior();
	}

	public function getType() { return 'dropdown'; }
	public function getEditTitle()
	{
		return 'Выбор одного верного варианта из выпадающего списка';
	}
	public function getPrototypeFilename() { return TaskType::PROTOTYPE_DIR.'task_dropdown.xml'; }

	protected function traverseStart(\SimpleXMLElement $elem, $params)
	{
		parent::traverseStart($elem, $params);
		if ($elem->getName() == 'answer')
			echo "$elem<select>";
		return true;
	} 

	protected function traverseEnd(\SimpleXMLElement $elem, $params)
	{
		if ($elem->getName() == 'answer')
			echo '</select>';
		parent::traverseEnd($elem, $params);
	} 

	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		parent::processAnswerElement($elem, $params);
		$opt = '<option';
		if ($this->correct) {
			$this->correct = false;
			$opt .= ' selected ';
		}
		$opt .= ">$elem</option>";
		echo $opt;
		return false;
	}	
}

?>
