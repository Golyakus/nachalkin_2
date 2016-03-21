<?php
namespace app\utils;

class InputTaskType extends TaskType 
{
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
	
	protected function processAnswerElement(\SimpleXMLElement $elem, $params)
	{
		extract($params);
		$isnumeric = false;
		parent::ProcessAnswerElement($elem, $params);
		if ($action == TaskType::RENDER_VIEW_ACTION)
		{
			if ($isnumeric)
				$t = 'numeric';
			else
				$t = 'text';
			$retval = "<input type=\"$t\" value=\"$elem\"/>";
			echo $retval;
		}
		return false;
	}

	protected $isnumeric = false;

	protected function onAttribute($attr, $val)
	{
		TaskType::onAttribute($attr, $val);
		if ($attr = 'numeric')
			$this->isnumeric = $val == 'true';
	}
}

?>

